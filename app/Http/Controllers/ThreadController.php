<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ThreadController extends Controller
{
    private $spamFree;

    public function __construct(SpamFree $spamFree)
    {
        $this->middleware('auth')->except(['show', 'index']);
        $this->spamFree = $spamFree;
    }

    public function index(Channel $channel , ThreadFilters $filters)
    {
         $threads = $this->getThreads($filters, $channel);
         $trending_threads=array_map('json_decode',
             Redis::zrevrange('trending_threads' , 0 , 4));
        if (request()->wantsJson()){
          return $threads;
        }
        return view('thread.index', [
            'threads'=>$threads ,
            'trending_threads'=>$trending_threads
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('thread.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_data=$this->validate($request, [
            'title' => 'required',
            'body' => ['required' , $this->spamFree],
            'channel_id' => 'required|exists:channels,id'
        ]);
        $validated_data['user_id']=auth()->user()->id;
        $thread = Thread::create($validated_data);
        return redirect($thread->path())->with('flash' , 'Thread Created Correctly');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread)
    {
        //
        Redis::zincrby('trending_threads' , 1 , json_encode([
            'title' =>$thread->title,
            'path'=>$thread->path()
        ]));
        return view('thread.show', [
            'thread'=>$thread ,
            'replies'=>$thread->replies()->paginate(20)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Thread $thread
     * @param Channel $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('delete' , $thread);
        try {
            $thread->delete();
        }catch (\Exception $e){
            abort(500 ,$e->getMessage());
        }
        if (request()->wantsJson()){
            return response([],204);
        }
        return redirect('/threads');

    }

    /**
     * @param ThreadFilters $filters
     * @param Channel $channel
     * @return mixed
     */
    public function getThreads(ThreadFilters $filters, Channel $channel)
    {
        $threads = Thread::with('channel')->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        $threads = $threads->latest()->paginate(25);
        return $threads;
    }
}

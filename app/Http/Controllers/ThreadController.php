<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\User;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }

    public function index(Channel $channel , ThreadFilters $filters)
    {
        $threads = $this->getThreads($filters, $channel);
        if (request()->wantsJson()){
          return $threads;
        }
        return view('thread.index', compact('threads'));
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
            'body' => 'required',
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
        $threads = $threads->latest()->get();
        return $threads;
    }
}

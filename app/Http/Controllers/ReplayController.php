<?php

namespace App\Http\Controllers;

use App\interceptions\Spam;
use App\Replay;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Tests\Feature\SpamTest;

class ReplayController extends Controller
{
    private   $spamFree;

    /**
     * ReplayController constructor.
     * @param $spam
     */
    public function __construct(SpamFree $spamFree)
    {
        $this->spamFree = $spamFree;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $channe_id,Thread $thread)
    {
       $replies = $thread->replies()->paginate(2);
       return response($replies, 200);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $channe_id,Thread $thread)
    {
        try {
            $this->validateReply();
            $thread = $thread->addReplay([
                'body'=>$request->input('body') ,
                'user_id'=>auth()->user()->id
            ]);
        }catch (\Exception $e){
            throw new \Exception('cannot save ur reply in this time',400);
        }
        if ($request->expectsJson()){
           return response($thread->load('owner') , 201);
        }
        //
        return  back()->with('flash' , 'reply created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Replay  $replay
     * @return \Illuminate\Http\Response
     */
    public function show(Replay $replay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Replay  $replay
     * @return \Illuminate\Http\Response
     */
    public function edit(Replay $replay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Replay  $replay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Replay $replay)
    {
        $this->authorize('update' , $replay);
        try {
            $data=$this->validateReply();
            $replay->update($data);

        }catch (\Exception $e){
            throw new \Exception('cannot update ur reply in this time',400);
        }

        return response([] ,200);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Replay  $replay
     * @return \Illuminate\Http\Response
     */
    public function destroy(Replay $replay)
    {
        $this->authorize('delete' ,$replay);
        try {
            $replay->delete();

        }catch (\Exception $e){
            abort('500' , $e->getMessage());
        }

        if (request()->expectsJson()){
            return  response(['deleted'] , 200);
        }

        return back();
        //
    }

    /**
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateReply(): array
    {
       $data= request()->validate( [
            'body' => ['required' , $this->spamFree]
        ]);
        return $data;
    }
}

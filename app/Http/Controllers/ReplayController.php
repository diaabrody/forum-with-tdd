<?php

namespace App\Http\Controllers;

use App\Replay;
use App\Thread;
use Illuminate\Http\Request;

class ReplayController extends Controller
{
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
        $this->validate($request ,[
            'body'=>'required'
        ]);

        $thread = $thread->addReplay([
            'body'=>$request->input('body') ,
            'user_id'=>auth()->user()->id
        ]);
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
        $data=$request->validate([
            'body'=>'required|string'
        ]);
        try {
            $replay->update($data);

        }catch (\Exception $e){
            abort('500' , $e->getMessage());
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
}

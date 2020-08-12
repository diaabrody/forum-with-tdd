<?php

namespace App\Http\Controllers;

use App\Replay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class FavouriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function store(Replay $replay){
        try {
            $replay->favourite();
        }catch (\Exception $e){
            abort(500 , $e->getMessage());
        }

       return response( ['created'] , 201);
    }
    public function destroy(Replay $replay){
        try {
            $replay->favourite();
        }catch (\Exception $e){
            abort(500 , $e->getMessage());
        }
        return response(['deleted'] , 200);
    }
}

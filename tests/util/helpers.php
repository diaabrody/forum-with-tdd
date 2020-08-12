<?php

 function make($class , $attributes=[]){
    return factory($class)->make($attributes);
}
 function create($class , $attributes =[] , $times = null){
    return factory($class ,$times)->create($attributes);
}


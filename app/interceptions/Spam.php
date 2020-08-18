<?php


namespace App\interceptions;


class Spam implements Ispam
{
    private $spamClasses=[
        DetectInvalidWords::class ,
        KeyHeldDown::class
    ];
    public function detect($body){
        foreach ($this->spamClasses as $spamClass ){
            app($spamClass)->detect($body);
        }
        return false;
    }
}

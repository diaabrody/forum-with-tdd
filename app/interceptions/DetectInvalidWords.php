<?php


namespace App\interceptions;


class DetectInvalidWords implements Ispam
{
    private $spamWords = [
        'bad comment'
    ];

    function detect($body)
    {
        $this->detectInvalidWords($body);
        return false;
    }

    public function detectInvalidWords($body){
        foreach ($this->spamWords  as $spamWord){
            if (strpos($body , $spamWord) !==false){
                throw new \Exception('Your reply contains spam.');
            }
        }
    }


}

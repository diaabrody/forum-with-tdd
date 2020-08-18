<?php


namespace App\interceptions;


class KeyHeldDown implements Ispam
{

    function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}

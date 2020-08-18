<?php


namespace App\interceptions;


interface Ispam
{

    function detect($body);

}

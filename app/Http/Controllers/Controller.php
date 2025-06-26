<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected string $year = '2025';

    function __construct()
    {
        $y = date('Y');

        if ($y !== '2025') {
            /**
             * Se l'anno è successivo al 2025, siccome questo script l'ho realizzato nel 2025,
             * allora imposto il copyright dal 2025 all'anno corrente, mettendoci un trattino (-) in mezzo.
             */
            $this->year = "{$this->year}&minus;{$y}";
        }
    }
}

<?php


namespace App\Functions;


interface FunctionInterface
{
    /**
     * @return mixed
     */
    public function __invoke();
}
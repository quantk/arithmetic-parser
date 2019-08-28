<?php


namespace App\Functions;


class Tan implements FunctionInterface
{
    /**
     * @var float|int
     */
    private $arg;


    /**
     * Sin constructor.
     * @param int|float $arg
     */
    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function __invoke()
    {
        return tan($this->arg);
    }
}
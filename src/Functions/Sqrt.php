<?php


namespace App\Functions;


class Sqrt implements FunctionInterface
{
    /**
     * @var float|int
     */
    private $arg;


    /**
     * Sqrt constructor.
     * @param int|float $arg
     */
    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function __invoke()
    {
        return sqrt($this->arg);
    }
}
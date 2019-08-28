<?php


namespace App;


use App\Functions\Cos;
use App\Functions\Sin;
use App\Functions\Sqrt;
use App\Functions\Tan;

class FunctionModel
{
    public const BUILT_IN_FUNCTIONS = [
        self::SQRT => Sqrt::class,
        self::COS  => Cos::class,
        self::SIN  => Sin::class,
        self::TAN  => Tan::class,

    ];
    // functions
    public const SQRT = 'sqrt';
    public const COS  = 'cos';
    public const SIN  = 'sin';
    public const TAN  = 'tan';
}
<?php


namespace App;


class Token
{
    // base tokens
    public const EOF           = 'eof';
    public const INT           = 'int';
    public const REAL          = 'real';
    public const PLUS          = '+';
    public const MINUS         = '-';
    public const REALDIV       = '/';
    public const MUL           = '*';
    public const POWER         = 'power';
    public const LPAREN        = '(';
    public const RPAREN        = ')';
    public const COMMA         = ',';
    public const FUNCTION_CALL = 'function_call';

    /**
     * @var string
     */
    public $type;
    /**
     * @var string|int|bool|float
     */
    public $value;

    /**
     * Token constructor.
     * @param string $type
     * @param bool|float|int|string $value
     */
    public function __construct(string $type, $value)
    {
        $this->type  = $type;
        $this->value = $value;
    }


}
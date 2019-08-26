<?php


namespace Tests;


use App\Token;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function assertTokenType(Token $token, string $operator)
    {
        static::assertSame($token->type, $operator);
    }

    public static function assertTokenValue(Token $token, $value)
    {
        static::assertSame($token->value, $value);
    }
}
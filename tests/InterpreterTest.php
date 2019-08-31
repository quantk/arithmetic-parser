<?php


namespace Tests;


use App\Exception\DivisionByZeroException;
use App\Exception\LexerException;
use App\Exception\ParseError;
use App\Exception\UnknownIdentifier;
use App\Interpreter;

class InterpreterTest extends TestCase
{
    /**
     * @throws LexerException
     * @throws ParseError
     * @throws UnknownIdentifier
     */
    public function testExpression()
    {
        $result = Interpreter::evaluate('2+2*2');
        static::assertSame($result, 6);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testWithUnaryAndReal()
    {
        $result = Interpreter::evaluate('-(2.5+2*2)');
        static::assertSame($result, -6.5);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testPower()
    {
        $result = Interpreter::evaluate('2**5');
        static::assertSame($result, 32);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testComplexPower()
    {
        $result = Interpreter::evaluate('((2+2)*5)**2');
        static::assertSame($result, 400);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testComplexPowerWithAnotherPowerOperator()
    {
        $result = Interpreter::evaluate('((2+2)*5)^2');
        static::assertSame($result, 400);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testSqrtFunction()
    {
        $result = Interpreter::evaluate('sqrt(4)');
        static::assertSame($result, 2.0);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testSinFunction()
    {
        $result = Interpreter::evaluate('sin(1)');
        static::assertSame($result, 0.8414709848);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testCosFunction()
    {
        $result = Interpreter::evaluate('cos(1)');
        static::assertSame($result, 0.54030230586);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testTanFunction()
    {
        $result = Interpreter::evaluate('tan(1)');
        static::assertSame($result, 1.55740772465);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testComplexSqrtFunction()
    {
        $result = Interpreter::evaluate('(2+2*2) / (sqrt(2+2*2+3))');
        static::assertSame($result, 2.0);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testComplexExpression()
    {
        $result = Interpreter::evaluate('(1.0 + 2.0 * 3.0 / ( 6.0*6.0 + 5.0*44.0)) - 0.0234375');
        static::assertSame($result, 1.0);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testDivisionByZero()
    {
        $this->expectException(DivisionByZeroException::class);
        $result = Interpreter::evaluate('5/0');
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testComplexExp()
    {
        $result = Interpreter::evaluate('2^2^3');
        static::assertSame($result, 256);
    }

    /**
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function testSuperComplexExpression()
    {
        $result = Interpreter::evaluate('33+331-33/4*4*12/4^2');
        static::assertSame($result, 339.25);
    }
}
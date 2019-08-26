<?php


namespace Tests;


use App\Exception\DivisionByZeroException;
use App\Exception\LexerException;
use App\Exception\ParseError;
use App\Interpreter;
use App\Lexer;
use App\Parser;
use App\Visitor;

class InterpreterTest extends TestCase
{
    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testExpression()
    {
        $interpreter = $this->createInterpreter('2+2*2');
        $result      = $interpreter->interpr();
        static::assertSame($result, 6);
    }

    /**
     * @param string $input
     * @return Interpreter
     * @throws LexerException
     */
    private function createInterpreter(string $input)
    {
        $lexer   = new Lexer($input);
        $parser  = new Parser($lexer);
        $visitor = new Visitor();
        return new Interpreter($parser, $visitor);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testWithUnaryAndReal()
    {
        $interpreter = $this->createInterpreter('-(2.5+2*2)');
        $result      = $interpreter->interpr();
        static::assertSame($result, -6.5);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testComplexExpression()
    {
        $interpreter = $this->createInterpreter('(1.0 + 2.0 * 3.0 / ( 6.0*6.0 + 5.0*44.0)) - 0.0234375');
        $result      = $interpreter->interpr();
        static::assertSame($result, 1.0);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testDivisionByZero()
    {
        $interpreter = $this->createInterpreter('5/0');
        $this->expectException(DivisionByZeroException::class);
        $interpreter->interpr();
    }
}
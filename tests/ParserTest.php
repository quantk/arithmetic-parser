<?php


namespace Tests;


use App\Exception\LexerException;
use App\Exception\ParseError;
use App\Lexer;
use App\Node\BinaryOperator;
use App\Node\Integer;
use App\Node\Real;
use App\Node\UnaryOperator;
use App\Parser;
use App\Token;

class ParserTest extends TestCase
{
    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testSimple()
    {
        $lexer  = new Lexer('(2+2)*2');
        $parser = new Parser($lexer);
        $ast    = $parser->parse();
        static::assertInstanceOf(BinaryOperator::class, $ast);
        static::assertInstanceOf(BinaryOperator::class, $ast->left);
        static::assertInstanceOf(Integer::class, $ast->left->left);
        static::assertInstanceOf(Token::class, $ast->left->operator);
        static::assertTokenType($ast->left->operator, Token::PLUS);
        static::assertInstanceOf(Integer::class, $ast->left->right);
        static::assertInstanceOf(Token::class, $ast->operator);
        static::assertTokenType($ast->operator, Token::MUL);
        static::assertInstanceOf(Integer::class, $ast->right);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testWithReal()
    {
        $lexer  = new Lexer('(2.5+2) * 444.2');
        $parser = new Parser($lexer);
        $ast    = $parser->parse();
        static::assertInstanceOf(BinaryOperator::class, $ast);
        static::assertInstanceOf(BinaryOperator::class, $ast->left);
        static::assertInstanceOf(Real::class, $ast->left->left);
        static::assertInstanceOf(Token::class, $ast->left->operator);
        static::assertTokenType($ast->left->operator, Token::PLUS);
        static::assertInstanceOf(Integer::class, $ast->left->right);
        static::assertInstanceOf(Token::class, $ast->operator);
        static::assertTokenType($ast->operator, Token::MUL);
        static::assertInstanceOf(Real::class, $ast->right);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testUnary()
    {
        $lexer  = new Lexer('--2');
        $parser = new Parser($lexer);
        $ast    = $parser->parse();
        static::assertInstanceOf(UnaryOperator::class, $ast);
        static::assertTokenType($ast->operator, Token::MINUS);
        static::assertInstanceOf(UnaryOperator::class, $ast->right);
        static::assertTokenType($ast->right->operator, Token::MINUS);
        static::assertInstanceOf(Integer::class, $ast->right->right);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     */
    public function testWithDiv()
    {
        $lexer  = new Lexer('(2+2)/2');
        $parser = new Parser($lexer);
        $ast    = $parser->parse();
        static::assertInstanceOf(BinaryOperator::class, $ast);
        static::assertInstanceOf(BinaryOperator::class, $ast->left);
        static::assertInstanceOf(Integer::class, $ast->left->left);
        static::assertInstanceOf(Token::class, $ast->left->operator);
        static::assertTokenType($ast->left->operator, Token::PLUS);
        static::assertInstanceOf(Integer::class, $ast->left->right);
        static::assertInstanceOf(Token::class, $ast->operator);
        static::assertTokenType($ast->operator, Token::REALDIV);
        static::assertInstanceOf(Integer::class, $ast->right);
    }
}
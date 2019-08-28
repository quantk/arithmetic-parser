<?php


namespace Tests;


use App\Exception\LexerException;
use App\Exception\ParseError;
use App\Exception\UnknownIdentifier;
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
     * @throws UnknownIdentifier
     */
    public function testSimple()
    {
        $lexer  = new Lexer('(2+2)*2');
        $parser = new Parser($lexer);
        /** @var BinaryOperator $ast */
        $ast    = $parser->parse();
        static::assertInstanceOf(BinaryOperator::class, $ast);
        /** @var BinaryOperator $visitable */
        $visitable = $ast->left;
        static::assertInstanceOf(BinaryOperator::class, $visitable);
        static::assertInstanceOf(Integer::class, $visitable->left);
        static::assertInstanceOf(Token::class, $visitable->operator);
        static::assertTokenType($visitable->operator, Token::PLUS);
        static::assertInstanceOf(Integer::class, $visitable->right);
        static::assertInstanceOf(Token::class, $ast->operator);
        static::assertTokenType($ast->operator, Token::MUL);
        static::assertInstanceOf(Integer::class, $ast->right);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     * @throws UnknownIdentifier
     */
    public function testWithReal()
    {
        $lexer  = new Lexer('(2.5+2) * 444.2');
        $parser = new Parser($lexer);
        /** @var BinaryOperator $ast */
        $ast    = $parser->parse();
        static::assertInstanceOf(BinaryOperator::class, $ast);
        /** @var BinaryOperator $left */
        $left = $ast->left;
        static::assertInstanceOf(BinaryOperator::class, $left);
        static::assertInstanceOf(Real::class, $left->left);
        static::assertInstanceOf(Token::class, $left->operator);
        static::assertTokenType($left->operator, Token::PLUS);
        static::assertInstanceOf(Integer::class, $left->right);
        static::assertInstanceOf(Token::class, $ast->operator);
        static::assertTokenType($ast->operator, Token::MUL);
        static::assertInstanceOf(Real::class, $ast->right);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     * @throws UnknownIdentifier
     */
    public function testUnary()
    {
        $lexer  = new Lexer('--2');
        $parser = new Parser($lexer);
        /** @var UnaryOperator $ast */
        $ast    = $parser->parse();
        static::assertInstanceOf(UnaryOperator::class, $ast);
        static::assertTokenType($ast->operator, Token::MINUS);
        /** @var UnaryOperator $visitable */
        $visitable = $ast->right;
        static::assertInstanceOf(UnaryOperator::class, $visitable);
        static::assertTokenType($visitable->operator, Token::MINUS);
        static::assertInstanceOf(Integer::class, $visitable->right);
    }

    /**
     * @throws LexerException
     * @throws ParseError
     * @throws UnknownIdentifier
     */
    public function testWithDiv()
    {
        $lexer  = new Lexer('(2+2)/2');
        $parser = new Parser($lexer);
        /** @var BinaryOperator $ast */
        $ast    = $parser->parse();
        static::assertInstanceOf(BinaryOperator::class, $ast);
        /** @var BinaryOperator $visitable */
        $visitable = $ast->left;
        static::assertInstanceOf(BinaryOperator::class, $visitable);
        static::assertInstanceOf(Integer::class, $visitable->left);
        static::assertInstanceOf(Token::class, $visitable->operator);
        static::assertTokenType($visitable->operator, Token::PLUS);
        static::assertInstanceOf(Integer::class, $visitable->right);
        static::assertInstanceOf(Token::class, $ast->operator);
        static::assertTokenType($ast->operator, Token::REALDIV);
        static::assertInstanceOf(Integer::class, $ast->right);
    }
}
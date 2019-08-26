<?php


namespace Tests;


use App\Exception\LexerException;
use App\Lexer;
use App\Token;

class LexerTest extends TestCase
{
    /**
     * @throws LexerException
     */
    public function testLexSimple()
    {
        $input = '2+2';
        $lexer = new Lexer($input);

        $two = $lexer->getNextToken();
        static::assertTokenType($two, Token::INT);
        static::assertTokenValue($two, 2);
        $operator = $lexer->getNextToken();
        static::assertTokenType($operator, Token::PLUS);
        static::assertTokenValue($operator, '+');
        $two = $lexer->getNextToken();
        static::assertTokenType($two, Token::INT);
        static::assertTokenValue($two, 2);
    }

    /**
     * @throws LexerException
     */
    public function testBrackets()
    {
        $input = '(2+2*(2+2))';
        $lexer = new Lexer($input);

        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::LPAREN);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::PLUS);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::MUL);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::LPAREN);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::PLUS);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::RPAREN);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::RPAREN);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::EOF);
    }

    /**
     * @throws LexerException
     */
    public function testLexComplex()
    {
        $input = '32+ 52.4-23*11/3';
        $lexer = new Lexer($input);

        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        static::assertTokenValue($token, 32);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::PLUS);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::REAL);
        static::assertTokenValue($token, 52.4);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::MINUS);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        static::assertTokenValue($token, 23);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::MUL);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        static::assertTokenValue($token, 11);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::REALDIV);
        $token = $lexer->getNextToken();
        static::assertTokenType($token, Token::INT);
        static::assertTokenValue($token, 3);
    }
}
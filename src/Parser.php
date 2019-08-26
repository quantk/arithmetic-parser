<?php


namespace App;


use App\Exception\ParseError;
use App\Node\BinaryOperator;
use App\Node\Integer;
use App\Node\Real;
use App\Node\UnaryOperator;

class Parser
{
    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @var Token
     */
    private $currentToken;

    /**
     * Parser constructor.
     * @param Lexer $lexer
     * @throws Exception\LexerException
     */
    public function __construct(
        Lexer $lexer
    )
    {
        $this->lexer        = $lexer;
        $this->currentToken = $this->lexer->getNextToken();
    }

    /**
     * @param string $tokenType
     * @throws Exception\LexerException
     * @throws ParseError
     */
    private function eat(string $tokenType): void
    {
        if ($this->currentToken->type === $tokenType) {
            $this->currentToken = $this->lexer->getNextToken();
        } else {
            throw new ParseError('Wrong token type');
        }
    }

    /**
     * @return Integer|Real|UnaryOperator|BinaryOperator
     * @throws Exception\LexerException
     * @throws ParseError
     */
    public function factor()
    {
        $token = $this->currentToken;

        if ($token->type === Token::PLUS) {
            $this->eat(Token::PLUS);
            return new UnaryOperator($token, $this->factor());
        }
        if ($token->type === Token::MINUS) {
            $this->eat(Token::MINUS);
            return new UnaryOperator($token, $this->factor());
        }

        if ($token->type === Token::INT) {
            $this->eat(Token::INT);
            return new Integer((int)$token->value);
        }

        if ($token->type === Token::REAL) {
            $this->eat(Token::REAL);
            return new Real((float)$token->value);
        }

        if ($token->type === Token::LPAREN) {
            $this->eat(Token::LPAREN);
            $node = $this->expr();
            $this->eat(Token::RPAREN);
            return $node;
        }

        throw new ParseError('Unknown factor type');
    }

    /**
     * @return BinaryOperator|Integer|Real|UnaryOperator
     * @throws Exception\LexerException
     * @throws ParseError
     */
    public function term()
    {
        $node = $this->factor();

        while (in_array($this->currentToken->type, [Token::MUL, Token::REALDIV], true)) {
            /** @var Token $operator */
            $operator = $this->currentToken;
            /** @psalm-suppress PossiblyNullArgument */
            $this->eat($this->currentToken->type);
            $node = new BinaryOperator($node, $operator, $this->factor());
        }

        return $node;
    }

    /**
     * @return BinaryOperator|Integer|Real|UnaryOperator
     * @throws Exception\LexerException
     * @throws ParseError
     */
    private function expr()
    {
        $node = $this->term();

        while (in_array($this->currentToken->type, [Token::PLUS, Token::MINUS], true)) {
            /** @var Token $operator */
            $operator = $this->currentToken;
            /** @psalm-suppress PossiblyNullArgument */
            $this->eat($this->currentToken->type);
            $node = new BinaryOperator($node, $operator, $this->term());
        }

        return $node;
    }

    /**
     * @return BinaryOperator|Integer|Real|UnaryOperator
     * @throws Exception\LexerException
     * @throws ParseError
     */
    public function parse()
    {
        return $this->expr();
    }
}
<?php


namespace App;


use App\Exception\ParseError;
use App\Node\BinaryOperator;
use App\Node\FunctionCall;
use App\Node\Integer;
use App\Node\Real;
use App\Node\UnaryOperator;
use App\Node\Visitable;

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
     * @throws Exception\UnknownIdentifier
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
     * @throws Exception\UnknownIdentifier
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
     * @return FunctionCall
     * @throws Exception\LexerException
     * @throws Exception\UnknownIdentifier
     * @throws ParseError
     */
    private function parseFunction(): FunctionCall
    {
        $token = $this->currentToken;
        $this->eat(Token::FUNCTION_CALL);
        $this->eat(Token::LPAREN);
        $args = [];

        while (true) {
            $args[] = $this->expr();
            if ($this->currentToken->type === Token::COMMA) {
                $this->eat(Token::COMMA);
            } else {
                break;
            }
        }

        $this->eat(Token::RPAREN);

        return new FunctionCall($token, $args);
    }

    /**
     * @return Visitable
     * @throws Exception\LexerException
     * @throws ParseError
     * @throws Exception\UnknownIdentifier
     */
    private function factor(): Visitable
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

        if ($token->type === Token::FUNCTION_CALL) {
            return $this->parseFunction();
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
     * @throws Exception\LexerException
     * @throws Exception\UnknownIdentifier
     * @throws ParseError
     */
    private function exponent(): Visitable
    {
        $node = $this->factor();

        while ($this->currentToken->type === Token::POWER) {
            $operator = $this->currentToken;
            $this->eat(Token::POWER);
            $node = new BinaryOperator($node, $operator, $this->exponent());
        }

        return $node;
    }

    /**
     * @return Visitable
     * @throws Exception\LexerException
     * @throws ParseError
     * @throws Exception\UnknownIdentifier
     */
    private function term(): Visitable
    {
        $node = $this->exponent();

        while (in_array($this->currentToken->type, [Token::MUL, Token::REALDIV], true)) {
            /** @var Token $operator */
            $operator = $this->currentToken;
            /** @psalm-suppress PossiblyNullArgument */
            $this->eat($this->currentToken->type);
            $node = new BinaryOperator($node, $operator, $this->exponent());
        }

        return $node;
    }

    /**
     * @return Visitable
     * @throws Exception\LexerException
     * @throws ParseError
     * @throws Exception\UnknownIdentifier
     */
    private function expr(): Visitable
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
     * @return Visitable
     * @throws Exception\LexerException
     * @throws ParseError
     * @throws Exception\UnknownIdentifier
     */
    public function parse(): Visitable
    {
        return $this->expr();
    }
}
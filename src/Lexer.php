<?php


namespace App;


use App\Exception\LexerException;
use App\Exception\UnknownIdentifier;

class Lexer
{
    /**
     * @var string
     */
    private $input;
    /**
     * @var string|null
     */
    private $currentChar;
    /**
     * @var int
     */
    private $pos = 0;

    /**
     * Lexer constructor.
     * @param string $input
     */
    public function __construct(
        string $input
    )
    {
        $this->input       = $input;
        $this->currentChar = $this->input[$this->pos];
    }

    private function isAlpha(string $char): bool
    {
        return (bool)preg_match('/[a-zA-Z_]/', $char);
    }

    /**
     * @return Token
     * @throws UnknownIdentifier
     */
    private function identifier(): Token
    {
        $identifier = '';
        while ($this->currentChar !== null && $this->isAlpha($this->currentChar)) {
            $identifier .= $this->currentChar;
            $this->advance();
        }

        $startPos = $this->pos;
        while ($this->input[$startPos] === ' ') {
            $startPos++;
        }
        $nextChar = $this->input[$startPos] ?? null;

        if ($nextChar === '(') {
            return new Token(Token::FUNCTION_CALL, $identifier);
        }

        throw new UnknownIdentifier('Unknown identifier ' . $identifier);
    }

    /**
     * @return Token
     * @throws LexerException
     * @throws UnknownIdentifier
     */
    public function getNextToken(): Token
    {
        while ($this->currentChar !== null) {
            $this->skipWhitespace();
            if ($this->isAlpha($this->currentChar)) {
                return $this->identifier();
            }
            if ($this->currentChar === ',') {
                $this->advance();
                return new Token(Token::COMMA, ',');
            }
            if ($this->currentChar === '+') {
                $this->advance();
                return new Token(Token::PLUS, '+');
            }
            if ($this->currentChar === '-') {
                $this->advance();
                return new Token(Token::MINUS, '-');
            }
            if ($this->currentChar === '^') {
                $this->advance();
                return new Token(Token::POWER, '^');
            }
            if ($this->currentChar === '*') {
                return $this->analyzeAsterisk();
            }
            if ($this->currentChar === '/') {
                $this->advance();
                return new Token(Token::REALDIV, '/');
            }
            if ($this->currentChar === '(') {
                $this->advance();
                return new Token(Token::LPAREN, '(');
            }
            if ($this->currentChar === ')') {
                $this->advance();
                return new Token(Token::RPAREN, ')');
            }

            if (is_numeric($this->currentChar)) {
                return $this->parseNumber();
            }
        }

        return new Token(Token::EOF, Token::EOF);
    }

    private function skipWhitespace(): void
    {
        if ($this->currentChar === ' ') {
            $this->advance();
        }
    }

    private function advance(): void
    {
        $this->pos++;
        $this->currentChar = $this->input[$this->pos] ?? null;
    }

    private function peek(): ?string
    {
        $pos = $this->pos + 1;
        return $this->input[$pos] ?? null;
    }

    /**
     * @return Token
     * @throws LexerException
     */
    private function parseNumber(): Token
    {
        if ($this->currentChar === null) {
            throw new LexerException('Cannot found current char');
        }
        $number = '';
        while (is_numeric($this->currentChar) || ($number !== '' && $this->currentChar === '.')) {
            $number .= $this->currentChar;
            $this->advance();
        }

        if ($this->validateNumber($number) === false) {
            throw new LexerException('Parsed number not valid');
        }

        if (strpos($number, '.')) {
            return new Token(Token::REAL, (float)$number);
        }

        return new Token(Token::INT, (int)$number);
    }

    private function validateNumber(string $number): bool
    {
        return (bool)preg_match('/^[\d]+\.?[\d]*/', $number);
    }

    /**
     * @return Token
     */
    private function analyzeAsterisk(): Token
    {
        if ($this->peek() === '*') {
            $this->advance();
            $this->advance();
            return new Token(Token::POWER, '**');
        }

        $this->advance();
        return new Token(Token::MUL, '*');
    }
}
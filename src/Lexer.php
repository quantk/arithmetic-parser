<?php


namespace App;


use App\Exception\LexerException;

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
     * @throws LexerException
     */
    public function __construct(
        string $input
    )
    {
        $this->input       = $input;
        $this->currentChar = $this->input[$this->pos];
        if ($this->currentChar === '') {
            throw new LexerException('Lexer input is empty');
        }
    }

    /**
     * @return Token
     * @throws LexerException
     */
    public function getNextToken(): Token
    {
        while ($this->currentChar !== null) {
            if ($this->currentChar === ' ') {
                $this->skipWhitespace();
            }

            if ($this->currentChar === '+') {
                $this->advance();
                return new Token(Token::PLUS, '+');
            }
            if ($this->currentChar === '-') {
                $this->advance();
                return new Token(Token::MINUS, '-');
            }
            if ($this->currentChar === '*') {
                $this->advance();
                return new Token(Token::MUL, '*');
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
}
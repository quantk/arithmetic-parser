<?php


namespace App;


class Interpreter
{
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var Visitor
     */
    private $visitor;

    /**
     * Interpreter constructor.
     * @param Parser $parser
     * @param Visitor $visitor
     */
    public function __construct(
        Parser $parser,
        Visitor $visitor
    )
    {
        $this->parser  = $parser;
        $this->visitor = $visitor;
    }

    /**
     * @param string $input
     * @return bool|float|int|string|null
     * @throws Exception\LexerException
     * @throws Exception\UnknownIdentifier
     */
    public static function evaluate(string $input)
    {
        $lexer       = new Lexer($input);
        $parser      = new Parser($lexer);
        $visitor     = new Visitor();
        $interpreter = new static($parser, $visitor);

        return $interpreter->interpr();
    }

    /**
     * @return bool|float|int|string|null
     * @throws Exception\LexerException
     * @throws Exception\ParseError
     * @throws Exception\UnknownIdentifier
     */
    public function interpr()
    {
        $ast = $this->parser->parse();
        return $this->visitor->visit($ast);
    }
}
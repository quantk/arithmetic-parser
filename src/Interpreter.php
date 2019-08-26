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
     * @return bool|float|int|string|null
     * @throws Exception\LexerException
     * @throws Exception\ParseError
     */
    public function interpr()
    {
        $ast = $this->parser->parse();
        return $this->visitor->visit($ast);
    }
}
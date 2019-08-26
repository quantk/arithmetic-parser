<?php


namespace App\Node;


use App\Exception\VisitorException;
use App\Token;
use App\Visitor;

class UnaryOperator implements Visitable
{
    /**
     * @var Token
     */
    public $operator;
    /**
     * @var BinaryOperator|Real|Integer|UnaryOperator
     */
    public $right;

    /**
     * UnaryOperator constructor.
     * @param Token $operator
     * @param BinaryOperator|Integer|Real|UnaryOperator $right
     */
    public function __construct(
        Token $operator,
        $right
    )
    {
        $this->operator = $operator;
        $this->right    = $right;
    }

    /**
     * @inheritDoc
     */
    public function accept(Visitor $visitor)
    {
        $right = $visitor->visit($this->right);
        if ($this->operator->type === Token::PLUS) {
            return +$right;
        }
        if ($this->operator->type === Token::MINUS) {
            return -$right;
        }

        throw new VisitorException('Unknown operator for UnaryOperator');
    }
}
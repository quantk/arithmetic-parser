<?php


namespace App\Node;


use App\Exception\DivisionByZeroException;
use App\Exception\VisitorException;
use App\Token;
use App\Visitor;

class BinaryOperator implements Visitable
{
    /**
     * @var BinaryOperator|Real|Integer|UnaryOperator
     */
    public $left;
    /**
     * @var Token
     */
    public $operator;
    /**
     * @var BinaryOperator|Real|Integer|UnaryOperator
     */
    public $right;

    /**
     * BinaryOperator constructor.
     * @param BinaryOperator|Integer|Real|UnaryOperator $left
     * @param Token $operator
     * @param BinaryOperator|Integer|Real|UnaryOperator $right
     */
    public function __construct($left, Token $operator, $right)
    {
        $this->left     = $left;
        $this->operator = $operator;
        $this->right    = $right;
    }

    /**
     * @inheritDoc
     */
    public function accept(Visitor $visitor)
    {
        /** @var int|float $leftValue */
        $leftValue = $visitor->visit($this->left);
        /** @var int|float $rightValue */
        $rightValue = $visitor->visit($this->right);
        switch ($this->operator->type) {
            case Token::PLUS:
                return $leftValue + $rightValue;
            case Token::MINUS;
                return $leftValue - $rightValue;
            case Token::MUL;
                return $leftValue * $rightValue;
            case Token::REALDIV;
                if ($rightValue === 0) {
                    throw new DivisionByZeroException('Division be zero not allowed');
                }
                return $leftValue / $rightValue;
        }

        throw new VisitorException('Unknown operator for BinaryOperator');
    }
}
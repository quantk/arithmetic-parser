<?php


namespace App\Node;


use App\Visitor;

interface Visitable
{
    /**
     * @param Visitor $visitor
     * @return BinaryOperator|UnaryOperator|int|float
     */
    public function accept(Visitor $visitor);
}
<?php


namespace App;


use App\Exception\RuntimeException;
use App\Node\Visitable;

class Visitor
{
    /**
     * @param Visitable $node
     * @return int|float
     */
    public function visit(Visitable $node)
    {
        $result = $node->accept($this);

        if (!is_int($result) && !is_float($result)) {
            throw new RuntimeException('Interpreter could return only int or float');
        }

        return $result;
    }
}
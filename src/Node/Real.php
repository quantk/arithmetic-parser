<?php


namespace App\Node;


use App\Visitor;

class Real implements Visitable
{
    /**
     * @var float
     */
    public $value;

    /**
     * Real constructor.
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function accept(Visitor $visitor)
    {
        return $this->value;
    }
}
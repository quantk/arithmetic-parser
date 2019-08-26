<?php


namespace App\Node;


use App\Visitor;

class Integer implements Visitable
{
    /**
     * @var int
     */
    public $value;

    /**
     * Integer constructor.
     * @param int $value
     */
    public function __construct(int $value)
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
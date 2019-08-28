<?php


namespace App\Node;


use App\Exception\UnknownFunction;
use App\FunctionModel;
use App\Functions\FunctionInterface;
use App\Token;
use App\Visitor;

class FunctionCall implements Visitable
{
    /**
     * @var Token
     */
    public $function;
    /**
     * @var array<Visitable>
     */
    public $args;

    /**
     * FunctionCall constructor.
     * @param Token $function
     * @param array<Visitable> $args
     */
    public function __construct(Token $function, array $args)
    {
        $this->function = $function;
        $this->args     = $args;
    }

    /**
     * @param Visitor $visitor
     * @return mixed
     * @psalm-suppress LessSpecificImplementedReturnType
     * @throws UnknownFunction
     * @throws \ReflectionException
     */
    public function accept(Visitor $visitor)
    {
        /** @var array<int|float> $args */
        $args = [];
        foreach ($this->args as $arg) {
            $args[] = $visitor->visit($arg);
        }

        $invokableFunctionClass = FunctionModel::BUILT_IN_FUNCTIONS[$this->function->value] ?? null;

        if ($invokableFunctionClass === null) {
            throw new UnknownFunction('Function ' . $this->function->value . ' is unknown');
        }

        $rInvokableFunction = new \ReflectionClass($invokableFunctionClass);
        /** @var FunctionInterface $invokable */
        /** @psalm-suppress MixedArgumentTypeCoercion */
        $invokable = $rInvokableFunction->newInstanceArgs($args);

        return $invokable->__invoke();
    }
}
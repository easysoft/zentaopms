<?php

namespace Misc\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveReturnType extends AbstractRector
{
    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition('Remove return type from method', [new CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function foo(): void
    {
    }
}
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
final class SomeClass
{
    public function foo()
    {
    }
}
CODE_SAMPLE
        )]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node) : ?Node
    {
        $node->returnType = null;
        return $node;
    }
}

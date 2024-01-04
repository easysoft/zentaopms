<?php

namespace Misc\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveFunctionType extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [Function_::class];
    }

    /**
     * @param Function_ $node
     */
    public function refactor(Node $node) : ?Node
    {
        $node->returnType = null;

        foreach ($node->getParams() as $param) {
            $param->type = null;
        }

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {

    }
}

<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkNode');

class thinkTransition  extends thinkNode
{
    protected static array $defaultProps = array
    (
        'type' => 'transition'
    );
}

<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkNodeBase');

class thinkTransition  extends thinkNodeBase
{
    protected static array $defaultProps = array
    (
        'type' => 'transition'
    );
}

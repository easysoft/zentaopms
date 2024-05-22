<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkStepBase');

class thinkNode  extends thinkStepBase
{
    protected static array $defaultProps = array
    (
        'type' => 'node'
    );
}

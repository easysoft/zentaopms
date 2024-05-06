<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkstep' . DS . 'v1.php';

class thinkTransition  extends thinkstep
{
    protected static array $defaultProps = array
    (
        'type' => 'transition'
    );
}

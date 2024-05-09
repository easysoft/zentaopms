<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'entitylist' . DS . 'v1.php';

class storyList extends entitylist
{
    protected static array $defaultProps = array
    (
        'type' => 'story'
    );
}

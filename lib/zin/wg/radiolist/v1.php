<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';

class radioList extends checkList
{
    protected static array $defaultProps = array
    (
        'type' => 'radio'
    );
}

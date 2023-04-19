<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class colorPicker extends input
{
    static $defaultProps =
    [
        'type' => 'color'
    ];
}

<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class timePicker extends input
{
    static $defaultProps =
    [
        'type' => 'time'
    ];
}

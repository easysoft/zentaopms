<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class datePicker extends input
{
    static $defaultProps =
    [
        'type' => 'date'
    ];
}

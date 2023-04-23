<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class fileInput extends input
{
    static $defaultProps =
    [
        'type' => 'file'
    ];
}

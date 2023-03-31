<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class switcher extends checkbox
{
    protected static $defaultProps = array
    (
        'typeClass' => 'switch switch'
    );
}

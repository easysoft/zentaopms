<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class radio extends checkbox
{
    protected static $defaultProps = array
    (
        'type' => 'radio'
    );
}

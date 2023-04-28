<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class datetimePicker extends input
{
    static $defaultProps = array(
        'type' => 'datetime-local'
    );
}

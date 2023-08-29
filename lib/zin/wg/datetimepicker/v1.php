<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class datetimePicker extends input
{
    protected static array $defaultProps = array(
        'type' => 'datetime-local'
    );
}

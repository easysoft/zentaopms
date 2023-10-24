<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class radio extends checkbox
{
    protected static array $defaultProps = array
    (
        'type' => 'radio'
    );
}

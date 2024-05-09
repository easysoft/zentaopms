<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'thinkchecklist' . DS . 'v1.php';

class thinkRadioList extends thinkCheckList
{
    protected static array $defaultProps = array
    (
        'type' => 'radio'
    );
}

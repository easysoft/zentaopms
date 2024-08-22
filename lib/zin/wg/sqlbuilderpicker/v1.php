<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuildercontrol' . DS . 'v1.php';

class sqlBuilderPicker extends sqlBuilderControl
{
    protected static array $defineProps = array(
        "name?: string",
        "label?: string",
        "items?: array",
        "value?: string",
        "placeholder?: string",
        'labelWidth?: string="80px"',
        'width?: string="60"',
        "suffix?: string",
        "error?: bool=false",
        "errorMsg?: string"
    );

    protected function build()
    {
        $this->setProp('type', 'picker');
        return parent::build();
    }
}

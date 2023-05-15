<?php
declare(strict_types=1);
namespace zin;

class commentBtn extends btn
{
    static $defineProps = array(
        'dataUrl?:string',
        'dataType?:string="html"',
        'icon?:string',
        'iconClass?:string',
        'text?:string',
        'square?:bool',
        'disabled?:bool',
        'active?:bool',
        'url?:string',
        'target?:string',
        'size?:string|int',
        'trailingIcon?:string',
        'trailingIconClass?:string',
        'caret?:string|bool',
        'hint?:string',
        'type?:string',
        'btnType?:string'
    );

    protected function getProps(): array
    {
        $dataUrl  = $this->prop('dataUrl');
        $dataType = $this->prop('dataType');
        $props    = parent::getProps();

        $props['data-toggle']    = 'modal';
        $props['data-type']      = 'ajax';
        $props['data-data-type'] = $dataType;
        $props['data-url']       = $dataUrl;

        return $props;
    }
}

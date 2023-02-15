<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class btngroup extends wg
{
    protected function build()
    {
        $btnGroup = h::div(setClass('btn-group'));
        foreach($this->prop('btns') as $props)
        {
            $btnGroup->addChild(btn()->prop($props));
        }
        return $btnGroup;
    }
}

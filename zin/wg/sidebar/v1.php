<?php
namespace zin;

class sidebar extends wg
{
    protected static $defineProps = 'side?:string="left",showToggle?:bool=true';

    protected function build()
    {
        list($side, $showToggle) = $this->prop(array('side', 'showToggle'));
        return div
        (
            setClass("sidebar sidebar-$side"),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children(),
            $showToggle ? div
            (
                set::class("sidebar-toggle sidebar-$side-toggle"),
                icon("angle-$side"),
                on::click("zui.toggleSidebar({side: '$side'})")
            ) : NULL
        );
    }
}

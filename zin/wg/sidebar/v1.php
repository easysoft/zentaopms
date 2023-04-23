<?php
namespace zin;

class sidebar extends wg
{
    protected static $defineProps = 'side?:string="left",showToggle?:bool=true';

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS()
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

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

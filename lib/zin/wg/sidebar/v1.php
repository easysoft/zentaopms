<?php
declare(strict_types=1);
namespace zin;

class sidebar extends wg
{
    protected static array $defineProps = array(
        'side?:string="left"',
        'showToggle?:bool=true'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): wg
    {
        list($side, $showToggle) = $this->prop(array('side', 'showToggle'));
        return div
        (
            setClass("sidebar sidebar-$side"),
            set($this->getRestProps()),
            $this->children(),
            $showToggle ? div
            (
                set::className("sidebar-toggle sidebar-$side-toggle"),
                icon("angle-$side"),
                on::click("zui.toggleSidebar({side: '$side'})")
            ) : null
        );
    }
}

<?php
namespace zin;

class searchToggle extends wg
{
    protected static $defineProps = 'open?:bool,module?:string=""';

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $lang;
        $module = $this->prop('module');
        return btn
        (
            set::class('ghost search-form-toggle'),
            set::icon('search'),
            set::text($lang->searchAB),
            set('data-module', $this->prop('module')),
            on::click("window.toggleSearchForm('$module');"),
            $this->prop('open') ? h::jsCall('~window.toggleSearchForm', $module) : null
        );
    }
}

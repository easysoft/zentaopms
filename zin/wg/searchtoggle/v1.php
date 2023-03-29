<?php
namespace zin;

class searchToggle extends wg
{
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
        global $lang;
        return btn
        (
            set::class('ghost search-form-toggle'),
            set::icon('search'),
            set::text($lang->searchAB),
            on::click('window.toggleSearchForm'),
            to::after()
        );
    }
}

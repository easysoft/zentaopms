<?php
declare(strict_types=1);
namespace zin;

class searchToggle extends wg
{
    protected static array $defineProps = array(
        'open?:bool',
        'module?:string=""',
        'formName?:string=""'
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
        global $lang;
        $module   = $this->prop('module');
        $formName = $this->prop('formName');
        $open     = $this->prop('open');
        if(is_null($open) && !empty($_GET['browseType'])) $open = $_GET['browseType'] === 'bySearch';
        return btn
        (
            set::className('ghost search-form-toggle'),
            set::icon('search'),
            set::text($lang->searchAB),
            set('data-module', $this->prop('module')),
            set('data-on', 'click'),
            set('data-do', "window.toggleSearchForm('$module', '$formName');"),
            $open ? h::jsCall('~window.toggleSearchForm', $module, $formName, true) : null
        );
    }
}

<?php
declare(strict_types=1);
namespace zin;

class searchToggle extends wg
{
    protected static array $defineProps = array(
        'target?: string',
        'open?: bool',
        'module?: string'
    );

    protected function checkErrors()
    {
        if($this->hasProp('formName')) trigger_error('[ZIN] The property "formName" is not supported in the "searchToggle()"', E_USER_WARNING);
    }

    protected function build(): wg
    {
        global $lang, $app;
        list($target, $module, $open) = $this->prop(array('target', 'module', 'open'));

        if(is_null($open) && !empty($_GET['browseType'])) $open = $_GET['browseType'] === 'bySearch';
        if(is_null($module)) $module = $app->rawModule;
        return btn
        (
            set::className('ghost search-form-toggle'),
            set::icon('search'),
            set::active($open),
            set::text($lang->searchAB),
            toggle::searchform(array('module' => $module, 'target' => $target)),
            $open ? h::jsCall('~zui.toggleSearchForm', array('module' => $module, 'target' => $target, 'show' => true)) : null
        );
    }
}

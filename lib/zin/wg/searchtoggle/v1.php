<?php
declare(strict_types=1);
namespace zin;

class searchToggle extends wg
{
    protected static array $defineProps = array(
        'target?: string',
        'open?: bool',
        'module?: string',
        'url?: string',
        'searchUrl?: string'
    );

    protected function checkErrors()
    {
        if($this->hasProp('formName')) trigger_error('[ZIN] The property "formName" is not supported in the "searchToggle()"', E_USER_WARNING);
    }

    protected function build(): wg
    {
        global $lang, $app, $config;
        list($target, $module, $open, $url, $searchUrl) = $this->prop(array('target', 'module', 'open', 'url', 'searchUrl'));

        if(is_null($open) && !empty($_GET['browseType'])) $open = $_GET['browseType'] === 'bySearch';
        if(is_null($module)) $module = $app->rawModule;

        if(isset($config->zin->mode) && $config->zin->mode == 'compatible')
        {
            if(is_null($url))       $url       = createLink('search', 'buildZinForm', 'module=' . $this->prop('module'));
            if(is_null($searchUrl)) $searchUrl = createLink('search', 'buildZinQuery');
        }

        return btn
        (
            set::className('ghost search-form-toggle'),
            set::icon('search'),
            set::active($open),
            set::text($lang->searchAB),
            toggle::searchform(array('module' => $module, 'target' => $target, 'url' => $url, 'searchUrl' => $searchUrl)),
            $open ? h::jsCall('~zui.toggleSearchForm', array('module' => $module, 'target' => $target, 'show' => true, 'url' => $url, 'searchUrl' => $searchUrl)) : null
        );
    }
}

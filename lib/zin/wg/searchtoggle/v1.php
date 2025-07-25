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
        'searchUrl?: string',
        'text?: string',
        'onSearch?: callback',  // jsRaw("(result) => console.log('onSearchForm', result)")
        'searchLoader?: string|array',
        'simple?: boolean'      // 是否为简单模式，不包含保存搜索条件和已保存的查询条件侧边栏。
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .nav-item + li > .search-form-toggle {margin-left: 0.5rem}
        .search-form-toggle.active,
        .show-search-form #featureBar .search-form-toggle {--skin-text: var(--color-primary-500); --skin-ring: var(--color-primary-500)}
        CSS;
    }

    protected function checkErrors()
    {
        if($this->hasProp('formName')) trigger_error('[ZIN] The property "formName" is not supported in the "searchToggle()"', E_USER_WARNING);
    }

    protected function build()
    {
        if(!common::hasPriv('search', 'buildForm')) return;

        global $lang, $app, $config;
        list($target, $module, $open, $url, $searchUrl, $text, $simple, $onSearch, $searchLoader) = $this->prop(array('target', 'module', 'open', 'url', 'searchUrl', 'text', 'simple', 'onSearch', 'searchLoader'));

        if(is_null($open))
        {
            $browseType = !empty($_GET['browseType']) ? $_GET['browseType'] : data('browseType');
            $open       = $browseType ? strtolower($browseType) == 'bysearch' : false;
        }
        if(is_null($module)) $module = $app->rawModule;
        if(empty($text)) $text = $lang->searchAB;

        if(isset($config->zin->mode) && $config->zin->mode == 'compatible')
        {
            if(is_null($url))       $url       = createLink('search', 'buildZinForm', 'module=' . $module);
            if(is_null($searchUrl)) $searchUrl = createLink('search', 'buildZinQuery');
        }

        $searchFormOptions = array('module' => $module, 'target' => $target, 'url' => $url);
        if(!empty($searchUrl))    $searchFormOptions['searchUrl'] = $searchUrl;
        if(!empty($simple))       $searchFormOptions['simple'] = $simple;
        if(!empty($onSearch))     $searchFormOptions['onSearch'] = $onSearch;
        if(!empty($searchLoader)) $searchFormOptions['searchLoader'] = $searchLoader;
        return btn
        (
            set::className('search-form-toggle rounded-full gray-300-outline size-sm'),
            set::icon('search'),
            set::active($open),
            set::text($text),
            toggle::searchform($searchFormOptions),
            $open ? on::init()->call('zui.toggleSearchForm', array_merge(array('show' => $open), $searchFormOptions)) : on::init()->do('$element.closest(".show-search-form").removeClass("show-search-form").find(".search-form[data-module=' . $module . ']").remove()')
        );
    }
}

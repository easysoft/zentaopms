<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'dropmenu' . DS . 'v1.php';

class heading extends wg
{
    protected static array $defineProps = array
    (
        'items?: array',            // 标题上显示的按钮。
        'showAppName?: bool=true',  // 是否自动显示当前应用名称。
        'dropmenu?: array'          // 1.5 级导航配置。
    );

    protected static array $defineBlocks = array
    (
        'toolbar'  => array('map' => 'toolbar'),
        'dropmenu' => array('map' => 'dropmenu')
    );

    protected function buildAppName()
    {
        list($tab, $lang) = data(array('app.tab', 'lang'));
        $icon = zget($lang->navIconNames, $tab, '');

        if(!in_array($tab, array('program', 'product', 'project')))
        {
            $nav = $lang->mainNav->$tab;
            list($title, $currentModule, $currentMethod, $vars) = explode('|', $nav);
            if($tab == 'execution') $currentMethod = 'all';
        }
        else
        {
            $currentModule = $tab;
            if($tab == 'program' or $tab == 'project') $currentMethod = 'browse';
            if($tab == 'product')                      $currentMethod = 'all';
        }

        $url = createLink($currentModule, $currentMethod);
        return item
        (
            set::url($url),
            set::hint($lang->$tab->common),
            ($tab == 'devops' || $tab == 'bi' || $tab == 'safe') ? setClass('font-brand') : null,
            set::icon($icon),
            set::text($lang->$tab->common)
        );
    }

    /**
     * Build dropmenu.
     *
     * @access protected
     * @return dropmenu
     */
    protected function buildDropmenu(): dropmenu|array|null
    {
        global $app, $config;
        if($this->hasBlock('dropmenu')) return $this->block('dropmenu');

        $moduleName = $app->rawModule;
        $methodName = $app->rawMethod;
        if(in_array("$moduleName-$methodName", $config->excludeDropmenuList)) return null;

        if(in_array($app->tab, $config->hasDropmenuApps))
        {
            $module = $app->tab;
            if($app->tab == 'qa') $module = 'product';
            if($app->tab == 'bi') $module = 'dimension';

            if($app->tab == 'qa' && ($moduleName == 'caselib' || data('isLibCase'))) $module = 'caselib';
            return new dropmenu(set::tab($module));
        }

        return null;
    }

    protected function buildToolbar()
    {
        $showAppName = $this->prop('showAppName');
        if($this->hasBlock('toolbar')) $this->prop('toolbar');
        return new toolbar
        (
            $showAppName ? $this->buildAppName() : null,
            set::btnClass('ghost'),
            set::items($this->prop('items')),
            $this->children()
        );
    }

    /**
     * Build.
     *
     * @access protected
     */
    protected function build(): wg
    {
        return div
        (
            set::id('heading'),
            $this->buildToolbar(),
            $this->buildDropmenu()
        );
    }
}

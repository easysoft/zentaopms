<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'dropmenu' . DS . 'v1.php';

class heading extends wg
{
    static $defineProps = array
    (
        'items?: array',            // 标题上显示的按钮。
        'showAppName?: bool=true',  // 是否自动显示当前应用名称。
        'dropmenu?: array'          // 1.5 级导航配置。
    );

    static $defineBlocks = array
    (
        'toolbar'  => array('map' => 'toolbar'),
        'dropmenu' => array('map' => 'dropmenu')
    );

    protected function buildAppName()
    {
        list($tab, $lang) = data(['app.tab', 'lang']);
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
            $tab == 'devops' ? setClass('num') : null,
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
       if($this->hasBlock('dropmenu')) return $this->block('dropmenu');

       $dropmenuProps = $this->prop('dropmenu');

       /**
        * 如果需要根据配置自动添加 dropmenu 就在这里进行处理。
        * If need to automatically add a dropmenu based on the configuration, handle it here.
        */
       if(empty($dropmenuProps)) return null;

       return new dropmenu(set($dropmenuProps));
    }

    protected function buildToolbar()
    {
        $showAppName = $this->prop('showAppName');
        if($this->hasBlock('toolbar')) $this->prop('toolbar');
        return new toolbar
        (
            $showAppName ? $this->buildAppName() : null,
            set::btnClass('primary'),
            set::items($this->prop('items')),
            $this->children()
        );
    }

    /**
     * Build.
     *
     * @access protected
     * @return object
     */
    protected function build()
    {
        $showAppName = $this->prop('showAppName');

        return div
        (
            set::id('heading'),
            $this->buildToolbar(),
            $this->buildDropmenu()
        );
    }
}

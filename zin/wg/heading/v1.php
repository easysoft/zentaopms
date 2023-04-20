<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';

class heading extends wg
{
    static $defineProps = 'items?:array, showAppName?:bool=true';

    protected function buildAppName()
    {
        list($tab, $lang) = data(['app.tab', 'lang']);
        $icon = zget($lang->navIcons, $tab, '');

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
            $tab == 'devops' ? set::class('num') : NULL,
            html($icon),
            span(set::class('text'), $lang->$tab->common),
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
            new toolbar
            (
                $showAppName ? $this->buildAppName() : NULL,
                set::btnClass('primary'),
                set::items($this->prop('items')),
                $this->children()
            )
        );
    }
}

<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'featurebar' . DS . 'v1.php';

class main extends wg
{
    static $defineBlocks = array
    (
        'menu' => array('map' => 'featureBar,nav,toolbar'),
        'sidebar' => array('map' => 'sidebar')
    );

    protected function buildMenu()
    {
        $menuBlocks = $this->block('menu');
        if(empty($menuBlocks)) return NULL;

        list($featureBarList, $navList, $toolbarList, $restList) = groupWgInList($menuBlocks, array('featureBar', 'nav', 'toolbar'));

        $featureBar = NULL;
        if(!empty($featureBarList)) $featureBar = $featureBarList[0];
        elseif(!empty($navList)) $featureBar = new featureBar($navList);

        $toolbar = NULL;
        if(!empty($toolbarList)) $toolbar = $toolbarList[0];
        if($toolbar instanceof wg && !$toolbar->hasProp('id')) $toolbar->setProp('id', 'actionBar');

        return div
        (
            set::id('mainMenu'),
            $featureBar,
            $toolbar,
            $restList
        );
    }

    protected function buildContent()
    {
        $leftSides  = array();
        $rightSides = array();
        $sidebars   = $this->block('sidebar');

        if(!empty($sidebars))
        {
            foreach($sidebars as $sidebar)
            {
                if($sidebar instanceof wg && $sidebar->prop('side') === 'left') $leftSides[] = $sidebar;
                else $rightSides[] = $sidebar;
            }
        }

        return div
        (
            set::id('mainContent'),
            $leftSides,
            set::class(empty($leftSides) && empty($rightSides) ? '' : 'row', empty($leftSides) ? '' : 'has-sidebar-left', empty($rightSides) ? '' : 'has-sidebar-right'),
            $this->children(),
            $rightSides
        );
    }

    protected function build()
    {
        return div
        (
            set::id('main'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            div
            (
                set::class('container'),
                $this->buildMenu(),
                $this->buildContent()
            )
        );
    }
}

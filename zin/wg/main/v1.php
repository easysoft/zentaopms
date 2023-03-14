<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'featurebar' . DS . 'v1.php';

class main extends wg
{
    static $defineBlocks = array
    (
        'menu' => array('map' => 'featureBar,nav,toolbar'),
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
        return div
        (
            set::id('mainContent'),
            $this->children()
        );
    }

    protected function build()
    {
        return div
        (
            set::id('main'),
            div
            (
                set::class('container'),
                $this->buildMenu(),
                $this->buildContent()
            )
        );
    }
}

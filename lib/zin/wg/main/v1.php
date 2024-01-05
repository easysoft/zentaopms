<?php
declare(strict_types=1);
/**
 * The main widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'featurebar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'mainnavbar' . DS . 'v1.php';

/**
 * 主要内容部件类。
 * The main widget class.
 *
 * @author Hao Sun
 */
class main extends wg
{
    /**
     * Define the blocks.
     *
     * @var array
     * @access protected
     */
    protected static array $defineBlocks = array
    (
        'navbar'  => array('map' => 'mainNavbar'),
        'menu'    => array('map' => 'featureBar,nav,toolbar'),
        'sidebar' => array('map' => 'sidebar')
    );

    /**
     * Define the properties.
     *
     * @access protected
     * @return wg
     */
    protected function buildMenu(): wg|null
    {
        $menuBlocks = $this->block('menu');
        if(empty($menuBlocks)) return null;

        list($featureBarList, $navList, $toolbarList, $restList) = groupWgInList($menuBlocks, array('featureBar', 'nav', 'toolbar'));

        $featureBar = null;
        if(!empty($featureBarList)) $featureBar = $featureBarList[0];
        elseif(!empty($navList)) $featureBar = new featureBar($navList);

        $toolbar = null;
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

    /**
     * Build main content.
     *
     * @access protected
     * @return wg
     */
    protected function buildContent()
    {
        $leftSides  = array();
        $rightSides = array();
        $sidebars   = $this->block('sidebar');

        if(!empty($sidebars))
        {
            foreach($sidebars as $sidebar)
            {
                if(!($sidebar instanceof wg)) continue;
                $sidebar->setDefaultProps(array('parent' => '#mainContainer'));
                if($sidebar->prop('side') === 'left') $leftSides[] = $sidebar;
                else $rightSides[] = $sidebar;
            }
        }

        $children     = $this->children();
        $hasLeftSide  = !empty($leftSides);
        $hasRightSide = !empty($rightSides);

        if($hasLeftSide || $hasRightSide)
        {
            $children = array
            (
                setClass('row', array('has-sidebar-left' => $hasLeftSide, 'has-sidebar-right' => $hasRightSide)),
                $leftSides,
                div
                (
                    $children,
                    setClass('main-content-cell')
                ),
                $rightSides
            );
        }

        return div
        (
            set::id('mainContent'),
            $children
        );
    }

    /**
     * Build main navbar from block.
     *
     * @access protected
     * @return array
     */
    protected function buildMainNavbar(): array|mainNavbar
    {
        $navbar = $this->block('navbar');
        if(!$navbar) return mainNavbar();
        return $navbar;
    }

    /**
     * Override the build method.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        return div
        (
            set::id('main'),
            set($this->getRestProps()),
            $this->buildMainNavbar(),
            div
            (
                setID('mainContainer'),
                setClass('container'),
                $this->buildMenu(),
                $this->buildContent()
            )
        );
    }
}

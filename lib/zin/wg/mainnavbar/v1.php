<?php
declare(strict_types=1);
/**
 * The mainNavbar widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

/**
 * 主内容导航（三级导航，mainNavbar）部件类。
 * The main navbar widget class.
 *
 * @author Hao Sun
 */
class mainNavbar extends nav
{
    /**
     * Define the blocks.
     *
     * @var array
     * @access protected
     */
    protected static array $defineBlocks = array
    (
        'left' => array('map' => 'dropdown'),
        'right' => array('map' => 'toolbar'),
    );

    protected function created()
    {
        global $app;

        $currentModule = $app->getModuleName();
        $currentMethod = $app->getMethodName();
        if($app->tab == 'admin') $app->control->loadModel('admin')->setMenu();

        \commonModel::setMainMenu();
        $activeMenu = \commonModel::printMainMenu(false);
        $items = \customModel::getModuleMenu($activeMenu);

        if($items)
        {
            $items = json_decode(json_encode($items), true);

            foreach($items as $key => $item)
            {
                if(empty($item['link']))
                {
                    unset($items[$key]);
                    continue;
                }
                if(empty($item['alias']))   $item['alias'] = '';
                if(empty($item['exclude'])) $item['exclude'] = '';

                $link = $item['link'];
                $items[$key]['url']     = commonModel::createMenuLink((object)$item, $app->tab);
                $items[$key]['data-id'] = $item['name'];

                $active    = '';
                $subModule = isset($item['subModule']) ? explode(',', $item['subModule']) : array();
                if($subModule && in_array($currentModule, $subModule)) $active = 'active';
                if($link['module'] == $currentModule && $link['method'] == $currentMethod) $active = 'active';
                if($link['module'] == $currentModule && strpos(",{$item['alias']},", ",{$currentMethod},") !== false) $active = 'active';
                if(strpos(",{$item['exclude']},", ",{$currentModule}-{$currentMethod},") !== false || strpos(",{$item['exclude']},", ",{$currentModule},") !== false) $active = '';
                $items[$key]['class'] = $active;

                unset($items[$key]['name']);
            }

            $this->setProp('items', $items);
        }
    }

    /**
     * Override the build method.
     *
     * @access protected
     * @return wg
     */
    protected function build(): h
    {
        if(!$this->prop('items')) return div();

        $leftBlock  = $this->block('left');
        $rightBlock = $this->block('right');

        return div
        (
            setID('mainNavbar'),
            div
            (
                setClass('container'),
                empty($leftBlock) ? null : div(setClass('main-navbar-left'), $leftBlock),
                parent::build(),
                empty($rightBlock) ? null : div(setClass('main-navbar-right'), $rightBlock)
            )
        );
    }
}

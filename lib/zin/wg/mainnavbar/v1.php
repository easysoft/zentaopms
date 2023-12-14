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
        'right' => array('map' => 'toolbar')
    );

    /**
     * Load the css file.
     *
     * @access public
     * @return string|false
     */
    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function created()
    {
        global $app;

        $currentModule = $app->getModuleName();
        $currentMethod = $app->getMethodName();
        if($app->tab == 'admin') $app->control->loadModel('admin')->setMenu();

        \commonModel::setMainMenu();
        $activeMenu = \commonModel::getActiveMainMenu();
        if(empty($activeMenu)) return false;

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
                $items[$key]['url']      = commonModel::createMenuLink((object)$item, $app->tab);
                $items[$key]['data-id']  = $item['name'];
                $items[$key]['data-app'] = $app->tab;

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
    protected function build(): wg
    {
        global $app, $config;

        $moduleName = $app->rawModule;
        $methodName = $app->rawMethod;

        if(!$this->prop('items') && !in_array("$moduleName-$methodName", $config->hasMainNavBar)) return wg();

        $leftBlock  = $this->block('left');
        $rightBlock = $this->block('right');
        if(empty($leftBlock)) $leftBlock = $this->buildSwitcher();

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

    /**
     * 构建2.5级下拉菜单。
     * Build switcher.
     *
     * @access protected
     * @return array
     */
    protected function buildSwitcher(): array|null
    {
        global $app, $config;

        $moduleName = $app->rawModule;
        $methodName = $app->rawMethod;

        if(in_array("$moduleName-$methodName", is_array($config->excludeSwitcherList) ? $config->excludeSwitcherList : array())) return null;

        if(in_array($moduleName, (isset($config->hasSwitcherModules) && is_array($config->hasSwitcherModules)) ? $config->hasSwitcherModules : array()) || in_array("$moduleName-$methodName", (isset($config->hasSwitcherMethods) && is_array($config->hasSwitcherMethods)) ? $config->hasSwitcherMethods : array()))
        {
            $ajaxMethod = 'ajaxSwitcherMenu';
            if($moduleName == 'testcase' && $app->tab == 'project') $moduleName = 'project';
            if($moduleName == 'testtask') $ajaxMethod = 'ajaxGetDropMenu';
            $fetcher = createLink($moduleName, $ajaxMethod, data('switcherParams'));
            return array(zui::dropmenu
                (
                    setID("{$moduleName}-menu"),
                    set('_id', 'switcher'),
                    set('data', data('data')),
                    set('_props', array('data-fetcher' => $fetcher)),
                    set(array('fetcher' => createLink($moduleName, $ajaxMethod, data('switcherParams')), 'text' => data('switcherText'), 'defaultValue' => data('switcherObjectID'))),
                    set($this->getRestProps())
                ));
        }

        return null;
    }
}

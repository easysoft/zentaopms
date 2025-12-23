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
    protected static array $defineProps = array
    (
        'items'        => '?array',
        'itemProps'    => '?array',
        'badgeMap'     => '?array',
        'active'       => '?string',
        'onRenderItem' => '?callback'
    );

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
    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        #mainNavbar {padding-left: 1rem; padding-right: 1rem;}
        #mainNavbar .main-navbar-left {position: absolute; z-index: 2;}
        #mainNavbar .main-navbar-left #switcher {position: relative; top: 5px; border: 1px solid rgb(var(--color-primary-500-rgb));}
        #mainNavbar .main-navbar-left #switcher > .dropmenu-btn {background: #FFF;}
        #mainNavbar .main-navbar-left #switcher > .dropmenu-btn:before {background: unset;}
        #mainNavbar .main-navbar-left #switcher:after, #mainNavbar .main-navbar-left #switcher:before {position: absolute; display: block; width: 0; height: 0; content: ' '; border-style: solid; border-width: 17px 0 17px 8px; top: -1px;}
        #mainNavbar .main-navbar-left #switcher:before {border-color: transparent transparent transparent rgb(var(--color-primary-500-rgb)); right: -8px;}
        #mainNavbar .main-navbar-left #switcher:after {border-color: transparent transparent transparent #FFF; right: -7px; border-radius: 2px;}
        #mainNavbar .main-navbar-left #switcher .icon-angle-right {display: none;}
        #mainNavbar .main-navbar-left #switcher .caret {color: rgb(var(--color-link-hover-rgb));}
        #mainNavbar .main-navbar-left #switcher .text {color: rgb(var(--color-primary-500-rgb));}
        #mainNavbar .nav[z-use-sortable] > li:hover {cursor: grab !important;}
        #mainNavbar .nav[z-use-sortable] > li > a:hover {cursor: grab !important;}
        CSS;
    }

    public static function getPageJS(): ?string
    {
        global $lang, $app;
        $app->loadLang('index');
        jsVar('langData', $lang->index->dock);
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function created()
    {
        global $app;

        $currentModule = $app->getModuleName();
        $currentMethod = $app->getMethodName();
        if($app->tab == 'admin')
        {
            $groupID = data('groupID');
            $app->control->loadModel('admin')->setMenu((int)$groupID);
        }

        /* When use workflow then set rawModule to moduleName. */
        if($currentModule == 'flow') $currentModule = $app->rawModule;

        \commonModel::replaceMenuLang();
        \commonModel::setMainMenu();
        $activeMenu = \commonModel::getActiveMainMenu();
        if(empty($activeMenu)) return false;

        $menu = \customModel::getModuleMenu($activeMenu);
        if($menu)
        {
            $menu         = json_decode(json_encode($menu), true);
            $items        = array();
            $badgeMap     = $this->prop('badgeMap');
            $onRenderItem = $this->prop('onRenderItem');
            $itemProps    = $this->prop('itemProps');
            $activeItem   = $this->prop('active') ?: data('activeMenuItem');

            foreach($menu as $key => $menuItem)
            {
                if(empty($menuItem['link']))
                {
                    unset($menu[$key]);
                    continue;
                }
                if(empty($menuItem['alias']))   $menuItem['alias'] = '';
                if(empty($menuItem['exclude'])) $menuItem['exclude'] = '';

                $item = array();
                $link = $menuItem['link'];
                $name = $menuItem['name'];
                $item['text']       = $menuItem['text'];
                $item['url']        = commonModel::createMenuLink((object)$menuItem, $app->tab);
                $item['hidden']     = !empty($menuItem['hidden']);
                $item['data-id']    = $name;
                $item['data-app']   = $app->tab;
                $item['data-group'] = $app->tab . '-' . $activeMenu;

                $active = '';
                if($activeItem && $activeItem == $name)
                {
                    $active = 'active';
                }
                else
                {
                    $subModule = isset($menuItem['subModule']) ? explode(',', $menuItem['subModule']) : array();
                    if($subModule && in_array($currentModule, $subModule)) $active = 'active';
                    if($link['module'] == $currentModule && $link['method'] == $currentMethod) $active = 'active';
                    if($link['module'] == $currentModule && strpos(",{$menuItem['alias']},", ",{$currentMethod},") !== false) $active = 'active';
                    if(strpos(",{$menuItem['exclude']},", ",{$currentModule}-{$currentMethod},") !== false || strpos(",{$menuItem['exclude']},", ",{$currentModule},") !== false) $active = '';
                }
                $item['class'] = $active;

                if($badgeMap && isset($badgeMap[$name])) $item['badge'] = array('text' => $badgeMap[$name], 'class' => 'label rounded gray-pale size-sm');
                if($itemProps) $item = array_merge($item, $itemProps);
                if(is_callable($onRenderItem)) $item = $onRenderItem($item, $menuItem);

                if(isset($menuItem['dropMenu']))
                {
                    $dropItems  = array();
                    $label      = $menuItem['text'];
                    $menuActive = '';
                    foreach($menuItem['dropMenu'] as $dropMenuKey => $dropMenuItem)
                    {
                        if(isset($dropMenuItem['hidden']) && $dropMenuItem['hidden']) continue;

                        if(empty($dropMenuItem['alias']))   $dropMenuItem['alias'] = '';
                        if(empty($dropMenuItem['exclude'])) $dropMenuItem['exclude'] = '';

                        list($dropMenuName, $dropMenuModule, $dropMenuMethod, $dropMenuParams) = explode('|', $dropMenuItem['link']);
                        $dropActive = '';
                        $dropModule = isset($dropMenuModule) ? $dropMenuModule : '';
                        $dropMethod = isset($dropMenuMethod) ? $dropMenuMethod : '';
                        $dropParams = isset($dropMenuParams) ? $dropMenuParams : '';
                        $dropLabel  = isset($dropMenuName) ? $dropMenuName : '';
                        $dropLink   = helper::createLink($dropModule, $dropMethod, $dropParams);

                        $subModule = isset($dropMenuItem['subModule']) ? explode(',', $dropMenuItem['subModule']) : array();
                        if($subModule && in_array($currentModule, $subModule)) $dropActive = 'active';
                        if($dropModule == $currentModule && $dropMethod == $currentMethod) $dropActive = 'active';
                        if($dropModule == $currentModule && strpos(",{$dropMenuItem['alias']},", ",{$currentMethod},") !== false) $dropActive = 'active';
                        if(strpos(",{$dropMenuItem['exclude']},", ",{$currentModule}-{$currentMethod},") !== false || strpos(",{$dropMenuItem['exclude']},", ",{$currentModule},") !== false) $dropActive = '';
                        if($dropActive)
                        {
                            $label      = $dropLabel;
                            $menuActive = 'active';
                        }

                        $dropItems[] = array(
                            'active'   => $dropActive ? true : false,
                            'data-id'  => $dropMenuKey,
                            'url'      => $dropLink,
                            'text'     => $dropLabel,
                            'data-app' => $app->tab
                        );
                    }

                    if(empty($dropItems)) continue;
                    $items[] = array
                    (
                        'type'     => 'dropdown',
                        'items'    => $dropItems,
                        'text'     => $label,
                        'data-id'  => $name,
                        'data-app' => $app->tab,
                        'trigger'  => 'hover',
                        'class'    => $menuActive
                    );
                }
                else
                {
                    $items[] = $item;
                }
            }

            jsVar('allMainNavbarItems', $items);
            jsVar('isTutorialMode', commonModel::isTutorialMode());
            $items = array_filter($items, function($item) { return empty($item['hidden']); });

            $this->setProp('items', $items);
        }
    }

    /**
     * Override the build method.
     *
     * @access protected
     * @return mixed
     */
    protected function build()
    {
        global $app, $config;

        $moduleName = $app->rawModule;
        $methodName = $app->rawMethod;

        if(!$this->prop('items') && !in_array("$moduleName-$methodName", $config->hasMainNavBar)) return null;

        $leftBlock  = $this->block('left');
        $rightBlock = $this->block('right');
        if(empty($leftBlock)) $leftBlock = $this->buildSwitcher();

        return div
        (
            setID('mainNavbar'),
            setClass('shadow'),
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
            if($moduleName == 'testcase' && $app->tab == 'project')   $moduleName = 'project';
            if($moduleName == 'testcase' && $app->tab == 'execution') $moduleName = 'execution';
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

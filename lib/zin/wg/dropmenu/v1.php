<?php
declare(strict_types=1);
/**
 * The dropmenu widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 1.5 级导航（dropmenu）部件类。
 * The dropmenu widget class.
 *
 * @author Hao Sun
 */
class dropmenu extends wg
{
    /**
     * Define the properties.
     *
     * @var array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'id?:       string="dropmenu"', // ID，当页面有多个 dropmenu 时确保有唯一的 ID。
        'tab?:      string,',           // 应用名。
        'module?:   string,',           // 模块名。
        'method?:   string,',           // 方法名。
        'objectID?: string,',           // 对象 ID。
        'extra?:    string,',           // 额外参数。
        'url?:      string',            // 异步获取下拉菜单选项数据的 URL，如果已经指定 module， method，objectID，extra 等参数则可以忽略。
        'text?:     string',            // 选择按钮上显示的文本。
        'cache?:    bool|int=true',     // 是否启用缓存。
        'data?:     array',             // 手动指定数据。
        'menuID?:   string',            // 指定下拉菜单的ID。
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

    /**
     * Override the build method.
     *
     * @access protected
     * @return wg
     */
    protected function build(): array
    {
        list($url, $text, $objectID, $cache, $tab, $module, $method, $extra, $id, $data, $menuID) = $this->prop(array('url', 'text', 'objectID', 'cache', 'tab', 'module', 'method', 'extra', 'id', 'data', 'menuID'));

        $app  = data('app');
        $lang = data('lang');

        if(empty($menuID))   $menuID   = $id . '-menu';
        if(empty($tab))      $tab      = $app->tab;
        if(empty($module))   $module   = $app->rawModule;
        if(empty($method))   $method   = $app->rawMethod;
        if(empty($extra))    $extra    = '';
        if(empty($objectID)) $objectID = data($tab . 'ID');
        if(empty($objectID))
        {
            $object = data($tab);
            if(isset($object->id)) $objectID = $object->id;
        }

        $branchMenu = null;
        if(($tab == 'product' || $tab == 'qa') and in_array($module, $app->config->hasBranchMenuModules))
        {
            if($objectID)
            {
                $product = $app->control->loadModel('product')->getByID((int)$objectID);
                if($product->type != 'normal')
                {
                    $branchID = data('branchID');

                    /* Get current branch name. */
                    $branchName = '';
                    if($branchID == 'all' || $branchID === '')
                    {
                        $branchID   = 'all';
                        $branchName = $lang->branch->all;
                    }
                    elseif($branchID == 0)
                    {
                        $branchName = $lang->branch->main;
                    }
                    elseif($branchID > 0)
                    {
                        $branchName = $app->control->loadModel('branch')->getById((int)$branchID);
                    }

                    $branchURL  = createLink('branch', 'ajaxGetDropMenu', "objectID=$objectID&branch=$branchID&module=$module&method=$method&extra=$extra");
                    $branchMenu = zui::dropmenu
                        (
                            setID('branch-dropmenu'),
                            set('_id', 'branch-dropmenu'),
                            set('_props', array('data-fetcher' => $branchURL)),
                            set('data', $data),
                            set(array('fetcher' => $branchURL, 'text' => $branchName, 'defaultValue' => $branchID)),
                            set($this->getRestProps())
                        );
                }
            }
        }

        if($tab == 'admin')
        {
            $currentMenuKey = $app->control->loadModel('admin')->getMenuKey();
            $text           = $lang->admin->menuList->{$currentMenuKey}['name'];
            $url            = createLink('admin', 'ajaxGetDropMenu', "currentMenuKey={$currentMenuKey}");
            $menuID         = 'admin-menu';
        }

        if(empty($url) && empty($data)) $url = createLink($tab, 'ajaxGetDropMenu', "objectID=$objectID&module=$module&method=$method&extra=$extra");
        if(empty($text) && !empty($tab) && !empty($objectID))
        {
            $object = $app->control->loadModel($tab)->getByID((int)$objectID);
            $text   = $object->name;
        }

        return array(zui::dropmenu
        (
            setID($menuID),
            set('_id', $id),
            set('_props', array('data-fetcher' => $url)),
            set('data', $data),
            set(array('fetcher' => $url, 'text' => $text, 'defaultValue' => $objectID, 'cache' => $cache)),
            set($this->getRestProps())
        ), $branchMenu);
    }
}

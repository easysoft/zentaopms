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
        'menuID?:   string'             // 指定下拉菜单的ID。
    );

    /**
     * Load the css file.
     *
     * @access public
     * @return string|false
     */
    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildDropmenu(string $menuID, string $id, null|string $url, null|string $text, null|array $data, string|int|null $defaultValue)
    {
        $cache = $this->prop('cache');
        return zui::dropmenu
        (
            setID($menuID),
            set('_id', $id),
            set('_props', array('data-fetcher' => $url, 'data-text' => $text, 'data-default-value' => $defaultValue, 'data-cache' => $cache)),
            set('data', $data),
            set(array('fetcher' => $url, 'text' => $text, 'defaultValue' => $defaultValue, 'cache' => $cache)),
            set($this->getRestProps())
        );
    }

    /**
     * Override the build method.
     *
     * @access protected
     * @return array
     */
    protected function build(): array
    {
        list($url, $text, $objectID, $cache, $tab, $module, $method, $extra, $id, $data, $menuID) = $this->prop(array('url', 'text', 'objectID', 'cache', 'tab', 'module', 'method', 'extra', 'id', 'data', 'menuID'));

        $app    = data('app');
        $lang   = data('lang');
        $config = data('config');

        if(empty($tab))      $tab      = $app->tab;
        if(empty($module))   $module   = $app->rawModule;
        if(empty($method))   $method   = $app->rawMethod;

        /* 打印工作流1.5级导航. */
        if($config->edition != 'open')
        {
            $flow = $app->control->loadModel('workflow')->getByModule($module);
            if($flow && $flow->buildin == '0' && $flow->belong) $tab = $flow->belong;
        }

        if(empty($menuID))   $menuID   = $id . '-menu';
        if(empty($extra))    $extra    = '';
        if(empty($objectID)) $objectID = data($tab . 'ID');
        if(empty($objectID))
        {
            $object = data($tab);
            if(isset($object->id)) $objectID = $object->id;
        }
        if($method == 'showerrornone' && empty($object) && empty($objectID)) return array();

        if($module == 'testcase' && in_array($method, array('view', 'edit')) && data('isLibCase')) $tab = 'caselib';

        if(strpos(',story-create,story-batchcreate,product-browse,projectstory-story,', ",$module-$method,") !== false) $extra = data('storyType');

        if($tab == 'dimension' && $module == 'tree' && $method == 'browsegroup') $extra = data('viewType');

        $branchMenu = null;
        if(($tab == 'product' || $tab == 'qa') && in_array($module, $app->config->hasBranchMenuModules) && !in_array("{$module}-{$method}", $app->config->excludeBranchMenu))
        {
            if($objectID)
            {
                $product = $app->control->loadModel('product')->getByID((int)$objectID);
                if($product->type != 'normal')
                {
                    $branchID = '';
                    if(!is_null(data('branch')))   $branchID = data('branch');
                    if(!is_null(data('branchID'))) $branchID = data('branchID');
                    if(strpos((string)$branchID, ',') !== false)
                    {
                        $branches = explode(',', $branchID);
                        $branchID = (!empty($_SESSION['branch']) || $app->session->branch === '0') && in_array($app->session->branch, $branches) ? $app->session->branch : $branches[0];
                    }
                    $app->control->loadModel('branch');

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
                        $branchName = $app->control->branch->getByID((string)$branchID);
                    }
                    if($module == 'testtask' && $method == 'browse') $extra = data('type');
                    if($app->moduleName == 'tree' && $app->methodName == 'browse')
                    {
                        $module = 'tree';
                        $method = 'browse';
                        $extra = data('viewType');
                    }
                    $branchURL  = createLink('branch', 'ajaxGetDropMenu', "objectID=$objectID&branch=$branchID&module=$module&method=$method&extra=$extra");
                    $branchMenu = $this->buildDropmenu('branch-dropmenu', 'branch-dropmenu', $branchURL, $branchName, $data, $branchID);
                }
            }
        }

        if($module == 'testtask' && in_array($method, array('cases', 'view', 'report', 'groupcase', 'linkcase')) && $app->tab == 'qa')
        {
            $testtaskUrl  = createLink('testtask', 'ajaxGetDropMenu', data('switcherParams'));
            $testtaskMenu = $this->buildDropmenu('testtask-dropmenu', 'testtask-dropmenu', $testtaskUrl, data('switcherText'), $data, data('switcherObjectID'));
        }

        if($tab == 'admin')
        {
            if($method == 'register') return array();
            $currentMenuKey = $app->control->loadModel('admin')->getMenuKey();
            $text           = isset($lang->admin->menuList->{$currentMenuKey}) ? $lang->admin->menuList->{$currentMenuKey}['name'] : $currentMenuKey;
            $url            = createLink('admin', 'ajaxGetDropMenu', "currentMenuKey={$currentMenuKey}");
            $menuID         = 'admin-menu';
            $objectID       = $currentMenuKey;
        }

        if($tab == 'caselib')    $objectID = data('libID');
        if($tab == 'feedback')
        {
            $objectID = data('productID');
            $extra    = data('viewType');
        }
        if($tab == 'demandpool') $objectID = data('poolID');
        if($tab == 'market')
        {
            $objectID = data('marketID');
            if(empty($objectID)) return array();
        }

        if(empty($url) && empty($data)) $url = createLink($tab, 'ajaxGetDropMenu', "objectID=$objectID&module=$module&method=$method&extra=$extra");
        if(empty($text) && !empty($tab) && !empty($objectID))
        {
            $loadModel = $tab == 'feedback' ? 'product' : $tab;
            $object    = $app->control->loadModel($loadModel)->getByID((int)$objectID);
            $text      = $object ? $object->name : '';
            if($tab == 'execution')
            {
                $project = $app->control->loadModel('project')->getByID((int)$object->project);
                $text = $project->name . ' / ' . $text;
            }
        }

        /* 如果是产品的 1.5 级导航，把当前产品的类型存入 session 以供后面使用。*/
        /* If the tab is product, save the product type to session. */
        if($tab == 'product' && isset($object)) $app->session->set('currentProductType', $object->type);

        return array
        (
            $this->buildDropmenu($menuID, $id, $url, $text, $data, $objectID),
            $branchMenu,
            isset($testtaskMenu) ? $testtaskMenu : null
        );
    }
}

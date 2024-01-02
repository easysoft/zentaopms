<?php
declare(strict_types=1);
/**
 * The zen file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lanzongjun <lanzongjun@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class treeZen extends tree
{
    /**
     * Set root.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  string $branch
     * @static
     * @access
     * @return object
     */
    protected function setRoot(int $rootID, string $viewType, string $branch): object
    {
        /* 产品线没有root。 Product line has no root. */
        if($viewType == 'line') return (object)array('id' => 0, 'name' => $this->lang->tree->mangeLine, 'rootType' => 'line');

        if(in_array($viewType, array('host', 'datasource')) || strpos($viewType, '_') !== false) return (object)array('id' => 0, 'name' => $this->lang->tree->manage, 'rootType' => 'line');

        /* 用例库的root是caselib，其他都是产品。 The root of caselib is caselib, others are product. */
        if(strpos($viewType, 'caselib') !== false)
        {
            $root = $this->loadModel('caselib')->getById($rootID);
            $root->rootType = 'lib';

            return $root;
        }

        $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
        $rootID = $this->product->getAccessibleProductID($rootID, $products);

        if($this->app->tab == 'product')
        {
            $this->product->setMenu($rootID, $branch, '', $viewType);
        }
        elseif($this->app->tab == 'qa')
        {
            $this->loadModel('qa')->setMenu($rootID, $branch);
        }
        elseif($this->app->tab == 'feedback')
        {
            $branch   = 'all';
            $products = $this->loadModel('feedback')->getGrantProducts();
            if(!$rootID) $rootID = key($products);
            $this->loadModel('feedback')->setMenu($rootID, $viewType, $viewType);
        }

        $root = $this->product->getByID($rootID);

        if(empty($root))
        {
            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => 'No product.'));
            $this->locate($this->createLink('product', 'create'));
        }

        $root->rootType = 'product';

        return $root;
    }

    /**
     * 获取产品的分支列表。
     * Get branches of product
     *
     * @param  object $product
     * @param  string $viewType
     * @param  int    $currentModuleID
     * @static
     * @access protected
     * @return array
     */
    protected function getBranches(object $product, string $viewType, int $currentModuleID): array
    {
        if(strpos('story|bug|case', $viewType) !== false)
        {
            if(!empty($product->type) && $product->type != 'normal')
            {
                $branches = $this->loadModel('branch')->getPairs($product->id, 'withClosed');
                if($currentModuleID)
                {
                    $currentModuleBranch = $this->dao->select('branch')->from(TABLE_MODULE)->where('id')->eq($currentModuleID)->fetch('branch');
                    $branchName = $branches[$currentModuleBranch];
                    unset($branches);
                    $branches[$currentModuleBranch] = $branchName;
                }
                return $branches;
            }
        }

        return array();
    }

    /**
     * 更新语言项。
     * Update lang.
     *
     * @param  string $viewType
     * @static
     * @access protected
     * @return void
     */
    protected function updateBrowseLang(string $viewType)
    {
        switch($viewType)
        {
            case 'host':
                $this->lang->tree->manage = $this->lang->tree->groupMaintenance;
                break;
            case 'caselib':
                $this->app->loadConfig('qa');
                foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
                break;
            case 'feedback':
            case 'ticket':
                $this->app->loadLang('feedback');
                $this->lang->tree->menu = $this->lang->feedback->menu;
            case 'dashboard':
                $this->lang->tree->menu = $this->lang->report->menu;
                break;
            case 'trainskill':
                $this->lang->tree->menu = $this->lang->trainskill->menu;
                break;
            case 'trainpost':
                $postBrowseType = $this->session->postBrowseType ? $this->session->postBrowseType : 'train';
                $this->lang->tree->menu = $postBrowseType == 'train' ? $this->lang->train->menu : $this->lang->company->menu;
                break;
            default:
                if(strpos($viewType, '_') !== false) // viewType is workflow building category.
                {
                    $this->updateWorkflowLang();
                }
                break;
        }

        $viewType = ucfirst($viewType);
        $this->lang->tree->manage = isset($this->lang->tree->$viewType) ? $this->lang->tree->$viewType : $this->lang->tree->common;
    }

    /**
     * 更新工作流语言项。
     * Update workflow lang.
     *
     * @param  string $viewType
     * @static
     * @access protected
     * @return void
     */
    protected function updateWorkflowLang($viewType)
    {
        $params = explode('_', $viewType);
        if(count($params) == 2)
        {
            $manageChild = 'manage' . ucfirst($viewType) . 'Child';
            if($viewType == 'datasource')
            {
                $datasourceID = $params[1];
                $datasource   = $this->loadModel('workflowdatasource', 'flow')->getByID($datasourceID);
                if($datasource)
                {
                    $this->lang->tree->$manageChild = $datasource->name;
                    $this->lang->tree->manage       = $datasource->name;
                }
            }
            else
            {
                $this->lang->tree->$manageChild = $this->lang->tree->common;
            }
        }
    }

    /**
     * 更新 rawModule。
     * Update rawModule.
     *
     * @param  int       $rootID
     * @param  string    $viewType
     * @access protected
     * @return void
     */
    protected function updateRawModule(int $rootID, string $viewType)
    {
        $manageLangs = array('');
        switch($viewType)
        {
            case 'bug':
                $this->app->loadConfig('qa');
                foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
                $this->app->rawModule = 'bug';
                break;
            case 'case':
                $this->app->loadConfig('qa');
                foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
                $this->app->rawModule = 'testcase';
                break;
            case 'caselib':
                $this->loadModel('caselib');
                $this->caselib->setLibMenu($this->caselib->getLibraries(), $rootID);
                $this->app->rawModule = 'caselib';
                break;
            case 'datasource':
                $this->app->rawModule = 'workflowdatasource';
                break;
            default:
                /* viewType is workflow building category. */
                if(strpos($viewType, '_') !== false)
                {
                    $params = explode('_', $viewType);
                    if(count($params) == 2) $this->app->rawModule = $params[0];
                }
        }
    }

    /**
     * 输出MHTML格式的OptionMenu。
     * Print OptionMenu typed of MHTML.
     *
     * @param  array  $optionMenu
     * @param  string $viewType
     * @param  int    $rootID
     * @static
     * @access protected
     * @return void
     */
    protected function printOptionMenuMHtml($optionMenu, $viewType, $rootID)
    {
        $changeFunc = '';
        if($viewType == 'bug' or $viewType == 'case') $changeFunc = "onchange='loadModuleRelated()'";
        if($viewType == 'task') $changeFunc = "onchange='setStories(this.value, $rootID)'";
        $field  = $fieldID ? "modules[$fieldID]" : 'module';
        $output = html::select("$field", $optionMenu, '', "class='input' $changeFunc");
        print($output);
    }

    /**
     * 输出Item格式的OptionMenu。
     * Print OptionMenu typed of Item.
     *
     * @param  array  $optionMenu
     * @static
     * @access protected
     * @return void
     */
    protected function printOptionMenuArray($optionMenu)
    {
        $output = array();
        foreach($optionMenu as $menuID => $menu) $output[] = array('text' => $menu, 'value' => $menuID, 'keys' => $menu);
        print(json_encode($output));
    }

    /**
     * 输出HTML格式的OptionMenu。
     * Print OptionMenu typed of HTML.
     *
     * @param  array  $optionMenu
     * @param  string $viewType
     * @param  int    $fieldID
     * @param  int    $currentModuleID
     * @static
     * @access protected
     * @return void
     */
    protected function printOptionMenuHtml($optionMenu, $viewType, $fieldID, $currentModuleID)
    {
        if($viewType == 'line')
        {
            $lineID = $this->dao->select('id')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq(0)->orderBy('id_desc')->limit(1)->fetch('id');

            $items = array();
            foreach($optionMenu as $moduleID => $moduleName) $items[] = array('text' => $moduleName, 'value' => $moduleID);

            $output = array('name' => 'line', 'defaultValue' => $lineID, 'items' => $items);
        }
        else
        {
            $field = $fieldID !== '' ? "modules[$fieldID]" : 'module';

            $currentModule   = $this->tree->getById($currentModuleID);
            $currentModuleID = (isset($currentModule->branch) and $currentModule->branch == 0) ? $currentModuleID : 0;

            $items = array();
            foreach($optionMenu as $moduleID => $moduleName) $items[] = array('text' => $moduleName, 'value' => $moduleID);

            $output = array('name' => $field, 'defaultValue' => $currentModuleID, 'items' => $items);
        }

        return print(json_encode($output));
    }
}

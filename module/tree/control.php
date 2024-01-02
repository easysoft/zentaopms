<?php
declare(strict_types=1);
/**
 * The control file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: control.php 5002 2013-07-03 08:25:39Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class tree extends control
{
    const NEW_CHILD_COUNT = 5;

    /**
     * 模块维护。
     * Module browse.
     *
     * @param  int    $rootID
     * @param  string $viewType story|bug|case|doc
     * @param  int    $currentModuleID
     * @param  string $branch
     * @param  string $from
     * @access public
     * @return void
     */
    public function browse(int $rootID, string $viewType, int $currentModuleID = 0, string $branch = 'all', string $from = '')
    {
        $this->updateBrowseLang($viewType);
        $this->updateRawModule($rootID, $viewType);

        /* 可以维护模块的类型：story, bug, case, feedback, caselib, ticket, line, 另外 doc, api在列表页面维护。*/
        $this->app->loadLang('host');
        $root = $this->treeZen->setRoot($rootID, $viewType, $branch);
        if($viewType == 'story')
        {
            $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
            $this->session->set('product', $rootID, $this->app->tab);

            $this->lang->modulePageNav = '';

            unset($products[$rootID]);
            $currentProduct = (int)key($products);

            $this->view->allProduct     = $products;
            $this->view->currentProduct = $currentProduct;
            $this->view->productModules = $this->tree->getOptionMenu($currentProduct, 'story');
        }

        if($viewType == 'feedback' or $viewType == 'ticket')
        {
            $syncConfig             = json_decode($this->config->global->syncProduct, true);
            $this->view->syncConfig = isset($syncConfig[$viewType]) ? $syncConfig[$viewType] : array();

        }

        $this->view->title           = $viewType == 'host' ? $this->lang->host->groupMaintenance : $this->lang->tree->manage;
        $this->view->rootID          = $root->id;
        $this->view->root            = $root;
        $this->view->productID       = $root->rootType == 'product' ? $root->id: 0;
        $this->view->libID           = $root->rootType == 'caselib' || $root->rootType == 'lib' ? $root->id : 0;
        $this->view->viewType        = $viewType;
        $this->view->placeholder     = $viewType == 'host' ? $this->lang->tree->groupName : $this->lang->tree->name;
        $this->view->sons            = $this->tree->getSons($rootID, $currentModuleID, $viewType, $branch);
        $this->view->currentModuleID = $currentModuleID;
        $this->view->parentModules   = $this->tree->getParents($currentModuleID);
        $this->view->branch          = $branch;
        $this->view->from            = $from;
        $this->view->tree            = $this->tree->getProductStructure($rootID, $viewType, $branch);
        $this->view->canBeChanged    = common::canModify($root->rootType, $root);
        $this->display();
    }

    /**
     * 任务模块维护。
     * Browse task module.
     *
     * @param  int $rootID
     * @param  int $productID
     * @param  int $currentModuleID
     * @access public
     * @return void
     */
    public function browseTask(int $rootID, int $productID = 0, int $currentModuleID = 0)
    {
        $this->lang->navGroup->tree = 'execution';

        /* Get execution. */
        $execution        = $this->loadModel('execution')->getById($rootID);
        $this->view->root = $execution;

        /* Get all associated products. */
        $products             = $this->loadModel('product')->getProducts($rootID);
        $this->view->products = $products;

        $executions = $this->execution->getPairs($this->session->project);

        /* Set menu. */
        $this->execution->setMenu($rootID);
        $this->lang->tree->menu      = $this->lang->execution->menu;
        $this->lang->tree->menuOrder = $this->lang->execution->menuOrder;

        if($this->app->tab == 'project') $this->view->projectID = $rootID;

        unset($executions[$rootID]);
        $parentModules = $this->tree->getParents($currentModuleID);
        $newModule     = (version_compare($execution->openedVersion, '4.1', '>') and $products) ? true : false;

        $title      = $execution->multiple ? $this->lang->tree->manageExecution : $this->lang->tree->manageProject;

        $this->view->title           = $title;
        $this->view->rootID          = $rootID;
        $this->view->productID       = $productID;
        $this->view->execution       = $execution;
        $this->view->allProject      = $executions;
        $this->view->newModule       = $newModule;
        $this->view->sons            = $this->tree->getTaskSons($rootID, $productID, $currentModuleID);
        $this->view->parentModules   = $parentModules;
        $this->view->currentModuleID = $currentModuleID;
        $this->view->tree            = $this->tree->getTaskStructure($rootID, $productID);
        $this->view->canBeChanged    = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->display();
    }

    /**
     * 编辑模块。
     * Edit a module.
     *
     * @param  int    $moduleID
     * @param  string $type
     * @param  string $branch
     * @access public
     * @return void
     */
    public function edit(int $moduleID, string $type, string $branch = '0')
    {
        if(!empty($_POST))
        {
            $this->tree->update($moduleID, $type);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('clodeModal' => true, 'load' => true));
        }

        $module = $this->tree->getById($moduleID);

        if($type == 'task')
        {
            $this->view->optionMenu = $this->tree->getTaskOptionMenu($module->root);
        }
        elseif($type != 'chart')
        {
            $this->view->optionMenu = $this->tree->getOptionMenu($module->root, $module->type, 0, (string)$module->branch, 'noMainBranch|nodeleted');
        }

        $this->view->name   = $type == 'line' ? $this->lang->tree->line : $this->lang->tree->name;
        $this->view->title  = $type == 'line' ? $this->lang->tree->manageLine : $this->lang->tree->edit;
        $this->view->module = $module;
        $this->view->type   = $type;
        $this->view->branch = $branch;
        $this->view->users  = $this->loadModel('user')->getPairs('noclosed|nodeleted', $module->owner);

        $showProduct = strpos('story|bug|case', $type) !== false ? true : false;
        if($showProduct)
        {
            $product = $this->loadModel('product')->getById($module->root);
            if($product->type != 'normal') $this->view->branches = $this->loadModel('branch')->getPairs($module->root, 'withClosed');
            $this->view->product  = $product;
            $this->view->products = $this->product->getPairs('', $product->program);
            if($product->shadow) $showProduct = false;
        }
        $this->view->showProduct = $showProduct;

        /* Remove self and childs from the $optionMenu. Because it's parent can't be self or childs. */
        $childs = $this->tree->getAllChildId($moduleID);
        foreach($childs as $childModuleID) unset($this->view->optionMenu[$childModuleID]);

        $this->display();
    }

    /**
     * 修复模块的path和grade字段。
     * Fix path, grades.
     *
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return void
     */
    public function fix(int $rootID, string $type)
    {
        $this->tree->fixModulePath($rootID, $type);
        echo js::alert($this->lang->tree->successFixed) . js::reload('parent');
    }

    /**
     * 更新模块排序。
     * Update modules' orders.
     *
     * @param  int    $root
     * @param  string $viewType
     * @param  int    $moduleID
     *
     * @access public
     * @return void
     */
    public function updateOrder(int $rootID = 0, string $viewType = '', int $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $this->tree->updateOrder($_POST['orders']);
            if($viewType == 'story' and !empty($rootID) and !empty($moduleID)) $this->loadModel('action')->create('module', $rootID, 'moved', '', $moduleID);
            die(js::reload('parent'));
        }
    }

    /**
     * 管理子模块。
     * Manage child modules.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @access public
     * @return void
     */
    public function manageChild(int $rootID, string $viewType)
    {
        if(!empty($_POST))
        {
            $moduleIDList = $this->tree->manageChild($rootID, $viewType);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $moduleIDList));
            if(isInModal())
            {
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "loadProductModules($rootID);"));
            }

            return $this->sendSuccess(array('load' => true));
        }
    }

    /**
     * 查看模块历史记录。
     * View module histories.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function viewHistory(int $productID)
    {
        $this->view->actions = $this->loadModel('action')->getList('module', $productID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 删除模块。
     * Delete a module.
     *
     * @param  int    $moduleID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete(int $moduleID, string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $module      = $this->tree->getByID($moduleID);
            $confirmLang = $this->lang->tree->confirmDelete;
            if($module->type == 'doc' or $module->type == 'api') $confirmLang = $this->lang->tree->confirmDeleteMenu;
            if($module->type == 'line') $confirmLang = $this->lang->tree->confirmDeleteLine;
            if($module->type == 'host') $confirmLang = $this->lang->tree->confirmDeleteHost;
            if(strpos($this->config->tree->groupTypes, ",$module->type,") !== false) $confirmLang = $this->lang->tree->confirmDeleteGroup;

            $confirmURL = $this->createLink($this->app->rawModule, $this->app->rawMethod, "moduleID=$moduleID&confirm=yes");

            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$confirmLang}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }
        else
        {
            $result = $this->tree->remove($moduleID);
            if(!$result) return;

            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }
    }

    /**
     * Ajax: 获取模块的下拉菜单。
     * Ajax: Get the option menu of modules.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  string $branch
     * @param  int    $rootModuleID
     * @param  string $returnType
     * @param  string $fieldID
     * @param  string $extra
     * @param  int    $currentModuleID
     * @access public
     * @return string the html select string.
     */
    public function ajaxGetOptionMenu(int $rootID, string $viewType = 'story', string $branch = 'all', int $rootModuleID = 0, string $returnType = 'html', string $fieldID = '', string $extra = 'nodeleted', int $currentModuleID = 0)
    {
        if($viewType == 'task')
        {
            $optionMenu = $this->tree->getTaskOptionMenu($rootID, 0, $extra);
        }
        else
        {
            $optionMenu = $this->tree->getOptionMenu($rootID, $viewType, $rootModuleID, $branch, $extra);
        }

        if(strpos($extra, 'excludeModuleID') !== false)
        {
            list($excludeModule, $noMainBranch) = explode(',', $extra);
            parse_str($excludeModule, $output);
            $excludeModuleID = $output['excludeModuleID'];

            if(strpos($extra, 'excludeRelated') !== false)
            {
                $childs = $this->tree->getAllChildId($excludeModuleID);
                foreach($childs as $childModuleID) unset($optionMenu[$childModuleID]);
            }
            else
            {
                if(isset($optionMenu[$excludeModuleID])) unset($optionMenu[$excludeModuleID]);
            }
        }

        if($returnType == 'items')
        {
            $this->printOptionMenuArray($optionMenu);
        }
        elseif($returnType == 'html')
        {
            $this->printOptionMenuHtml($optionMenu, $viewType, $fieldID, $currentModuleID);
        }
        elseif($returnType == 'mhtml')
        {
            $this->printOptionMenuMHtml($optionMenu, $viewType, $rootID);

            $changeFunc = '';
            if($viewType == 'bug' or $viewType == 'case') $changeFunc = "onchange='loadModuleRelated()'";
            if($viewType == 'task') $changeFunc = "onchange='setStories(this.value, $rootID)'";
            $field  = $fieldID ? "modules[$fieldID]" : 'module';
            $output = html::select("$field", $optionMenu, '', "class='input' $changeFunc");
            return print($output);
        }
        elseif($returnType == 'json')
        {
            print(json_encode($optionMenu));
        }
    }

    /**
     * Ajax: 获取drop菜单。
     * Ajax: get drop menu.
     *
     * @param  int    $rootID
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $rootID, string $module, string $method, string $extra = '')
    {
        $this->view->productID = $rootID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;

        $viewType = $module;
        if($module == 'bug') $viewType = 'bug';
        if($module == 'testcase') $viewType = 'case';

        $modules       = $this->tree->getOptionMenu($rootID, $viewType);
        $modulesPinyin = common::convert2Pinyin($modules);

        $this->view->link          = $viewType == 'caselib' ? helper::createLink($module, $method, "rootID=%s&type=byModule&param=%s") : helper::createLink($module, $method, "rootID=%s&branch=&type=byModule&param=%s");
        $this->view->viewType      = $viewType;
        $this->view->modules       = $modules;
        $this->view->modulesPinyin = $modulesPinyin;
        $this->display();
    }

    /**
     * Ajax: 获取模块列表。
     * Ajax: get modules.
     *
     * @param  int    $productID
     * @param  string $viewType
     * @param  int    $branchID
     * @param  int    $number
     * @param  int    $currentModuleID
     * @param  string $from showImport
     * @access public
     * @return string the html select string.
     */
    public function ajaxGetModules(int $productID, string $viewType = 'story', string $branchID = '0', int $number = 0, int $currentModuleID = 0, string $from = '')
    {
        $currentModule   = $this->tree->getById($currentModuleID);
        $currentModuleID = (isset($currentModule->branch) and $currentModule->branch == 0) ? $currentModuleID : 0;

        $modules = $this->tree->getOptionMenu($productID, $viewType, $startModuleID = 0, $branchID);
        $modules = empty($modules) ? array('' => '/') : $modules;

        if($viewType == 'bug' || $viewType == 'case')
        {
            $moduleList = array();
            foreach($modules as $moduleID => $moduleName) $moduleList[] = array('value' => $moduleID, 'text' => $moduleName);
            return $this->send(array('modules' => $moduleList, 'currentModuleID' => $currentModuleID));
        }

        if($this->viewType == 'json') return print(array('modules' => $modules, 'currentModuleID' => $currentModuleID));

        $moduleName = ($viewType == 'bug' and $from != 'showImport') ? "modules[$number]" : "module[$number]";
        echo html::select($moduleName, $modules, $currentModuleID, 'class=form-control');
    }

    /**
     * Ajax: 获取一个模块的子模块。
     * Ajax: get a module's son modules.
     *
     * @param  int    $moduleID
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return string json_encoded modules.
     */
    public function ajaxGetSonModules(int $moduleID, int $rootID = 0, string $type = 'story')
    {
        $modules = $this->dao->select('id,name,short')->from(TABLE_MODULE)
            ->where('root')->eq($rootID)
            ->andWhere('parent')->eq((int)$moduleID)
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
        echo json_encode($modules);
    }

    /**
     * Ajax: 创建模块。
     * Ajax: Create module.
     *
     * @access public
     * @return void
     */
    public function ajaxCreateModule()
    {
        if(!helper::isAjaxRequest()) return $this->send(array('result' => 'fail', 'message' => ''));;

        $module = $this->tree->createModule();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => implode('\n', dao::getError())));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }
}

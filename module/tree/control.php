<?php

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
        $this->loadModel('product');
        $placeholder = $this->lang->tree->name;

        if($this->app->tab == 'product' and strpos($viewType, 'doc') === false)
        {
            $this->product->setMenu($rootID, $branch, 0, '', $viewType);
        }
        elseif($this->app->tab == 'qa' and $viewType != 'caselib')
        {
            $this->loadModel('qa')->setMenu($rootID, $branch);
        }
        elseif($this->app->tab == 'project' and strpos($viewType, 'doc') === false)
        {
            $this->loadModel('project')->setMenu($this->session->project);

            $products = $this->product->getProducts($this->session->project, 'all', '', false);
            if($viewType == 'case') $this->lang->modulePageNav = $this->product->select($products, $rootID, 'tree', 'browse', 'case', $branch);
        }
        elseif($this->app->tab == 'feedback')
        {
            $branch   = 'all';
            $products = $this->loadModel('feedback')->getGrantProducts();
            if(!$rootID) $rootID = key($products);
            $this->loadModel('feedback')->setMenu($rootID, $viewType, $viewType);
        }

        /* According to the type, set the module root and modules. */
        if(strpos('story|bug|case', $viewType) !== false)
        {
            $product = $this->loadModel('product')->getById($rootID);
            if(empty($product))
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => 'No product.'));
                $this->locate($this->createLink('product', 'create'));
            }

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
                $this->view->branches = $branches;
            }
            $this->view->root = $product;

            /* Determines whether an object is editable. */
            $canBeChanged = common::canModify('product', $product);
        }
        elseif(strpos($viewType, 'doc') !== false)
        {
            /* The viewType is doc. */
            $this->loadModel('doc');
            $viewType         = 'doc';
            $lib              = $this->doc->getLibById($rootID);
            $this->view->root = $lib;
        }
        elseif(strpos($viewType, 'api') !== false)
        {
            /* The viewType is doc. */
            $this->loadModel('doc');
            $viewType         = 'api';
            $lib              = $this->doc->getLibById($rootID);
            $title            = $this->lang->tree->manageCustomDoc;
            $position[] = html::a($this->createLink('api', 'index', "libID=$rootID"), $lib->name);
            $position[] = $this->lang->tree->manageCustomDoc;
            $this->view->root = $lib;
        }
        elseif(strpos($viewType, 'caselib') !== false)
        {
            $this->loadModel('caselib');
            $lib               = $this->caselib->getById($rootID);
            $this->view->root  = $lib;
            $this->view->libID = $rootID;
        }

        if($viewType == 'story')
        {
            /* Set menu.*/
            $products = $this->product->getPairs($mode = '', $programID = 0, $append = '', 'all');
            if($this->app->tab == 'project') $this->view->projectID = $this->session->project;

            $rootID = $this->product->getAccessibleProductID($rootID, $products);
            $this->session->set('product', $rootID, $this->app->tab);

            unset($products[$rootID]);
            $currentProduct = (int)key($products);

            $this->lang->modulePageNav  = '';
            $this->view->allProduct     = $products;
            $this->view->currentProduct = $currentProduct;
            $this->view->productModules = $this->tree->getOptionMenu($currentProduct, 'story');
            $this->view->productID      = $rootID;

            $title = $this->lang->tree->manageProduct;
        }
        elseif($viewType == 'bug')
        {
            $this->app->loadConfig('qa');
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
            $this->app->rawModule = 'bug';

            $title      = $this->lang->tree->manageBug;
            $position[] = html::a($this->createLink('bug', 'browse', "product=$rootID"), $product->name);
            $position[] = $this->lang->tree->manageBug;

            $this->view->productID = $rootID;
        }
        elseif($viewType == 'feedback' or $viewType == 'ticket')
        {
            $this->app->loadLang('feedback');
            $this->lang->tree->menu = $this->lang->feedback->menu;
            $productItem = $this->product->getById($rootID);
            $root                   = new stdclass();
            $root->name             = !empty($rootID) ? $productItem->name : $this->lang->feedback->common;
            $this->view->root       = $root;
            $syncConfig             = json_decode($this->config->global->syncProduct, true);
            $this->view->syncConfig = isset($syncConfig[$viewType]) ? $syncConfig[$viewType] : array();

            $title      = $this->lang->tree->{$viewType == 'feedback' ? 'manageFeedback' : 'manageTicket'};
            $position[] = html::a($this->createLink('feedback', 'admin'), $this->lang->tree->manageFeedback);
        }
        elseif($viewType == 'case')
        {
            $this->app->loadConfig('qa');
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
            $this->app->rawModule = 'testcase';

            $title      = $this->lang->tree->manageCase;
            $position[] = html::a($this->createLink('testcase', 'browse', "product=$rootID"), $product->name);
            $position[] = $this->lang->tree->manageCase;

            $this->view->productID = $rootID;
        }
        elseif($viewType == 'caselib')
        {
            $this->app->loadConfig('qa');
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';

            $this->caselib->setLibMenu($this->caselib->getLibraries(), $rootID);
            $this->app->rawModule = 'caselib';

            $title      = $this->lang->tree->manageCaseLib;
            $position[] = html::a($this->createLink('caselib', 'browse', "libID=$rootID"), $lib->name);
            $position[] = $this->lang->tree->manageCaseLib;
        }
        elseif(strpos($viewType, 'doc') !== false)
        {
            $this->lang->navGroup->tree = 'doc';

            if($from == 'product')
            {
                $productID = $lib->product;
                unset($this->lang->product->menu->set['subModule']);

                $products = $this->product->getPairs();
                $productID = $this->product->getAccessibleProductID($productID, $products);
                $this->session->set('product', $productID, $this->app->tab);
            }
            elseif($from == 'project')
            {
                $this->lang->navGroup->tree  = 'project';

                /* The project parameter needs to be present when the tree module belongs to the project grouping. */
                if($this->session->docList && $this->session->project && strpos($this->session->docList, 'project') === false) $this->session->set('docList', $this->session->docList . '?project=' . $this->session->project, 'project');
            }

            if($from == 'doc') $this->lang->navGroup->doc = 'doc';
            $type                        = $lib->product ? 'product' : ($lib->project ? 'project' : ($lib->execution ? 'execution' : 'custom'));
            $this->lang->tree->menu      = $this->lang->doc->menu;
            $this->lang->tree->menuOrder = $this->lang->doc->menuOrder;

            $title      = $this->lang->tree->manageCustomDoc;
            $position[] = html::a($this->createLink('doc', 'browse', "libID=$rootID"), $lib->name);
            $position[] = $this->lang->tree->manageCustomDoc;
        }
        elseif($viewType == 'line')
        {
            $products = $this->product->getPairs('', $this->session->project);

            $this->product->setMenu($products, $rootID, $branch, 'line', '', 'line');
            $this->lang->tree->menu      = $this->lang->product->menu;
            $this->lang->tree->menuOrder = $this->lang->product->menuOrder;

            unset($products[$rootID]);
            $currentProduct = key($products);

            $this->view->allProduct     = $products;
            $this->view->currentProduct = $currentProduct;
            $this->view->productModules = $this->tree->getOptionMenu($currentProduct, 'line');

            $title      = $this->lang->tree->manageLine;
            $position[] = $this->lang->tree->manageLine;
        }
        elseif($viewType == 'dashboard')
        {
            $root       = new stdclass();
            $root->name = $this->lang->dashboard->common;

            $this->view->root       = $root;
            $this->lang->tree->menu = $this->lang->report->menu;

            $title      = $this->lang->tree->manageDashboard;
            $position[] = $this->lang->tree->manageDashboard;
        }
        elseif($viewType == 'trainskill')
        {
            $this->lang->tree->menu = $this->lang->trainskill->menu;

            $title      = $this->lang->tree->manageTrainskill;
            $position[] = $this->lang->tree->manageTrainskill;
        }
        elseif($viewType == 'trainpost')
        {
            $postBrowseType = $this->session->postBrowseType ? $this->session->postBrowseType : 'train';
            if($postBrowseType == 'train')
            {
                $this->lang->tree->menu = $this->lang->train->menu;
            }
            else
            {
                $this->lang->tree->menu = $this->lang->company->menu;
            }

            $title      = $this->lang->tree->manageTrainpost;
            $position[] = $this->lang->tree->manageTrainpost;
        }
        elseif($viewType == 'report')
        {
            $title      = $this->lang->tree->manageReport;
            $position[] = $this->lang->tree->manageReport;

            $root = new stdclass();
            $root->name = $this->lang->tree->report;
            $this->view->root = $root;
        }
        elseif(strpos($viewType, 'datasource') !== false)
        {
            $params = explode('_', $viewType);
            if(count($params) == 2)
            {
                $datasourceID = $params[1];
                $manageChild  = 'manage' . ucfirst($viewType) . 'Child';
                $datasource   = $this->loadModel('workflowdatasource', 'flow')->getByID($datasourceID);
                if($datasource)
                {
                    $this->app->rawModule = 'workflowdatasource';

                    $title      = $datasource->name;
                    $position[] = $datasource->name;

                    $this->lang->tree->$manageChild = $title;

                    $root = new stdclass();
                    $root->name = $title;
                    $this->view->root = $root;
                }
            }
        }
        /* viewType is workflow building category. */
        elseif(strpos($viewType, '_') !== false)
        {
            $params = explode('_', $viewType);
            if(count($params) == 2)
            {
                $this->app->rawModule = $params[0];

                $manageChild  = 'manage' . ucfirst($viewType) . 'Child';

                $title      = $this->lang->tree->common;
                $position[] = $this->lang->tree->common;

                $this->lang->tree->$manageChild = $title;

                $root = new stdclass();
                $root->name = $title;
                $this->view->root = $root;
            }
        }
        elseif(strpos($viewType, 'host') !== false)
        {
            $position = array();
            $title       = $this->lang->tree->groupMaintenance;
            $placeholder = $this->lang->tree->groupName;

            $root = new stdclass();
            $root->id   = 0;
            $root->name = $this->lang->tree->groupMaintenance;

            $this->view->productID = 0;
            $this->view->root      = $root;
        }

        if($this->app->tab == 'project' and strpos($viewType, 'doc') === false)
        {
            $project = $this->loadModel('project')->getByID($this->session->project);
            if(empty($project->hasProduct)) $this->view->root->name = $project->name;
        }

        $this->view->title           = $title;
        $this->view->position        = $position;
        $this->view->rootID          = $rootID;
        $this->view->viewType        = $viewType;
        $this->view->placeholder     = $placeholder;
        $this->view->sons            = $this->tree->getSons($rootID, $currentModuleID, $viewType, $branch);
        $this->view->currentModuleID = $currentModuleID;
        $this->view->parentModules   = $this->tree->getParents($currentModuleID);
        $this->view->branch          = $branch;
        $this->view->from            = $from;
        $this->view->tree            = $this->tree->getProductStructure($rootID, $viewType, $branch);
        $this->view->canBeChanged    = isset($canBeChanged) ? $canBeChanged : true;
        $this->display();
    }

    /**
     * Browse task module.
     *
     * @param  int $rootID
     * @param  int $productID
     * @param  int $currentModuleID
     * @access public
     * @return void
     */
    public function browseTask($rootID, $productID = 0, $currentModuleID = 0)
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
        $position[] = html::a($this->createLink('execution', 'task', "executionID=$rootID"), $execution->name);
        $position[] = $this->lang->tree->manageExecution;

        $this->view->title           = $title;
        $this->view->position        = $position;
        $this->view->rootID          = $rootID;
        $this->view->productID       = $productID;
        $this->view->execution       = $execution;
        $this->view->allProject      = $executions;
        $this->view->newModule       = $newModule;
        $this->view->modules         = $this->tree->getTaskTreeMenu($rootID, $productID, $rooteModuleID = 0, array('treeModel', 'createTaskManageLink'), 'allModule');
        $this->view->sons            = $this->tree->getTaskSons($rootID, $productID, $currentModuleID);
        $this->view->parentModules   = $parentModules;
        $this->view->currentModuleID = $currentModuleID;
        $this->view->tree            = $this->tree->getTaskStructure($rootID, $productID);
        $this->view->canBeChanged    = common::canModify('execution', $execution); // Determines whether an object is editable.
        $this->display();
    }

    /**
     * Edit a module.
     *
     * @param  int $moduleID
     * @access public
     * @return void
     */
    public function edit($moduleID, $type, $branch = 0)
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
            $this->view->optionMenu = $this->tree->getOptionMenu($module->root, $module->type, 0, $module->branch, 'noMainBranch|nodeleted');
        }

        if($type == 'doc')
        {
            $docLib   = $this->loadModel('doc')->getLibById($module->root);
            $objectID = isset($docLib->{$docLib->type}) ? $docLib->{$docLib->type} : 0;
            $this->view->libs = $this->doc->getLibs($docLib->type, '', '', $objectID, 'book');
        }

        if($type == 'doc' or $type == 'api')
        {
            $name  = $this->lang->tree->dir;
            $title = $this->lang->tree->editDir;
        }
        elseif($type == 'line')
        {
            $name  = $this->lang->tree->line;
            $title = $this->lang->tree->manageLine;
        }
        else
        {
            $name  = $this->lang->tree->name;
            $title = $this->lang->tree->edit;
        }

        $this->view->name   = $name;
        $this->view->title  = $title;
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

        $this->view->hiddenProduct = false;
        if($this->app->tab == 'project')
        {
            $project = $this->loadModel('project')->getByID($this->session->project);
            $this->view->hiddenProduct = empty($project->hasProduct);
        }

        $this->display();
    }

    /**
     * Fix path, grades.
     *
     * @param  string $root
     * @param  string $type
     * @access public
     * @return void
     */
    public function fix($root, $type)
    {
        $this->tree->fixModulePath($root, $type);
        echo js::alert($this->lang->tree->successFixed) . js::reload('parent');
    }

    /**
     * Update modules' orders.
     *
     * @param  int    $root
     * @param  string $viewType
     * @param  int    $moduleID
     *
     * @access public
     * @return void
     */
    public function updateOrder($rootID = 0, $viewType = '', $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $this->tree->updateOrder($_POST['orders']);
            if($viewType == 'story' and !empty($rootID) and !empty($moduleID)) $this->loadModel('action')->create('module', $rootID, 'moved', '', $moduleID);
            die(js::reload('parent'));
        }
    }

    /**
     * Manage child modules.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @access public
     * @return void
     */
    public function manageChild($rootID, $viewType)
    {
        if(!empty($_POST))
        {
            $moduleIDList = $this->tree->manageChild($rootID, $viewType);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $moduleIDList));
            return $this->sendSuccess(array('load' => true));
        }
    }

    /**
     * Delete a module.
     *
     * @param  int    $moduleID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($moduleID, $confirm = 'no')
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
            $result = $this->tree->delete($moduleID);
            if(!$result) return;

            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }
    }

    /**
     * AJAX: Get the option menu of modules.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  string $branch
     * @param  int    $rootModuleID
     * @param  string $returnType
     * @param  string $fieldID
     * @param  bool   $needManage
     * @param  string $extra
     * @param  int    $currentModuleID
     * @access public
     * @return string the html select string.
     */
    public function ajaxGetOptionMenu(int $rootID, string $viewType = 'story', string $branch = '0', int $rootModuleID = 0, string $returnType = 'html', string $fieldID = '', bool $needManage = false, string $extra = 'nodeleted', int $currentModuleID = 0)
    {
        if($viewType == 'task')
        {
            $optionMenu = $this->tree->getTaskOptionMenu($rootID, 0, $extra);
        }
        else
        {
            $optionMenu = $this->tree->getOptionMenu($rootID, $viewType, $rootModuleID, (int)$branch, $extra);
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
            $output = array();
            foreach($optionMenu as $menuID => $menu) $output[] = array('text' => $menu, 'value' => $menuID, 'keys' => $menu);
            return print(json_encode($output));
        }

        if($returnType == 'html')
        {
            //Code for task #5081.
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
        if($returnType == 'mhtml')
        {
            $changeFunc = '';
            if($viewType == 'bug' or $viewType == 'case') $changeFunc = "onchange='loadModuleRelated()'";
            if($viewType == 'task') $changeFunc = "onchange='setStories(this.value, $rootID)'";
            $field  = $fieldID ? "modules[$fieldID]" : 'module';
            $output = html::select("$field", $optionMenu, '', "class='input' $changeFunc");
            return print($output);
        }
        if($returnType == 'json') die(json_encode($optionMenu));
    }

    /**
     * Ajax get drop menu.
     *
     * @param  int    $rootID
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($rootID, $module, $method, $extra = '')
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
     * AJAX: get modules.
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
    public function ajaxGetModules($productID, $viewType = 'story', $branchID = 0, $number = 0, $currentModuleID = 0, $from = '')
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
     * AJAX: get a module's son modules.
     *
     * @param  int    $moduleID
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return string json_encoded modules.
     */
    public function ajaxGetSonModules($moduleID, $rootID = 0, $type = 'story')
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
     * View module histories.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function viewHistory($productID)
    {
        $this->view->actions = $this->loadModel('action')->getList('module', $productID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Create module by ajax.
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

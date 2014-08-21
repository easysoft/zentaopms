<?php
/**
 * The control file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: control.php 5002 2013-07-03 08:25:39Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class tree extends control
{
    const NEW_CHILD_COUNT = 10;

    /**
     * Module browse.
     * 
     * @param  int    $rootID 
     * @param  string $viewType         story|bug|case|doc
     * @param  int    $currentModuleID 
     * @access public
     * @return void
     */
    public function browse($rootID, $viewType, $currentModuleID = 0)
    {
        /* According to the type, set the module root and modules. */
        if(strpos('story|bug|case', $viewType) !== false)
        {
            $product = $this->loadModel('product')->getById($rootID);
            $this->view->root = $product;
            $this->view->productModules = $this->tree->getOptionMenu($rootID, 'story');
        }
        /* The viewType is doc. */
        elseif(strpos($viewType, 'doc') !== false)
        {
            $this->loadModel('doc');
            if($rootID == 'product' or $rootID == 'project')
            {
                $viewType = $rootID . 'doc';
                $lib = new stdclass();
                $lib->id   = $rootID;
                $lib->name = $this->lang->doc->systemLibs[$rootID];
                $this->view->root = $lib;
            }
            else
            {
                $viewType = 'customdoc';
                $lib = $this->loadModel('doc')->getLibById($rootID);
                $this->view->root = $lib;
            }
        }

        if($viewType == 'story')
        {
            $this->lang->set('menugroup.tree', 'product');
            $this->product->setMenu($this->product->getPairs(), $rootID, 'story');
            $this->lang->tree->menu      = $this->lang->product->menu;
            $this->lang->tree->menuOrder = $this->lang->product->menuOrder;

            $products = $this->product->getPairs();
            unset($products[$rootID]);
            $currentProduct = key($products);

            $this->view->allProduct     = $products;
            $this->view->currentProduct = $currentProduct;
            $this->view->productModules = $this->tree->getOptionMenu($currentProduct, 'story');

            $title      = $product->name . $this->lang->colon . $this->lang->tree->manageProduct;
            $position[] = html::a($this->createLink('product', 'browse', "product=$rootID"), $product->name);
            $position[] = $this->lang->tree->manageProduct;
        }
        elseif($viewType == 'bug')
        {
            $this->loadModel('bug')->setMenu($this->product->getPairs(), $rootID);
            $this->lang->tree->menu      = $this->lang->bug->menu;
            $this->lang->tree->menuOrder = $this->lang->bug->menuOrder;
            $this->lang->set('menugroup.tree', 'qa');

            $title      = $product->name . $this->lang->colon . $this->lang->tree->manageBug;
            $position[] = html::a($this->createLink('bug', 'browse', "product=$rootID"), $product->name);
            $position[] = $this->lang->tree->manageBug;
        }
        elseif($viewType == 'case')
        {
            $this->loadModel('testcase')->setMenu($this->product->getPairs(), $rootID);
            $this->lang->tree->menu      = $this->lang->testcase->menu;
            $this->lang->tree->menuOrder = $this->lang->testcase->menuOrder;
            $this->lang->set('menugroup.tree', 'qa');

            $title      = $product->name . $this->lang->colon . $this->lang->tree->manageCase;
            $position[] = html::a($this->createLink('testcase', 'browse', "product=$rootID"), $product->name);
            $position[] = $this->lang->tree->manageCase;
        }
        elseif(strpos($viewType, 'doc') !== false)
        {
            $this->doc->setMenu($this->doc->getLibs(), $rootID, 'doc');
            $this->lang->tree->menu      = $this->lang->doc->menu;
            $this->lang->tree->menuOrder = $this->lang->doc->menuOrder;
            $this->lang->set('menugroup.tree', 'doc');

            $title      = $lib->name . $this->lang->colon . $this->lang->tree->manageCustomDoc;
            $position[] = html::a($this->createLink('doc', 'browse', "libID=$rootID"), $lib->name);
            $position[] = $this->lang->tree->manageCustomDoc;
        }

        $parentModules = $this->tree->getParents($currentModuleID);
        $this->view->title           = $title;
        $this->view->position        = $position;
        $this->view->rootID          = $rootID;
        $this->view->viewType        = $viewType;
        $this->view->modules         = $this->tree->getTreeMenu($rootID, $viewType, $rooteModuleID = 0, array('treeModel', 'createManageLink'));
        $this->view->sons            = $this->tree->getSons($rootID, $currentModuleID, $viewType);
        $this->view->currentModuleID = $currentModuleID;
        $this->view->parentModules   = $parentModules;
        $this->display();
    }

    /**
     * Browse task module.
     * 
     * @param  int    $rootID 
     * @param  int    $productID 
     * @param  int    $currentModuleID 
     * @access public
     * @return void
     */
    public function browseTask($rootID, $productID = 0, $currentModuleID = 0)
    {
        $project = $this->loadModel('project')->getById($rootID);
        $this->view->root = $project;

        $products = $this->project->getProducts($rootID);
        $this->view->products = $products;

        $this->lang->set('menugroup.tree', 'project');
        $this->project->setMenu($this->project->getPairs(), $rootID);
        $this->lang->tree->menu      = $this->lang->project->menu;
        $this->lang->tree->menuOrder = $this->lang->project->menuOrder;

        $projects = $this->project->getPairs();
        unset($projects[$rootID]);
        $currentProject = key($projects);
        $parentModules  = $this->tree->getParents($currentModuleID);
        $newModule      = (version_compare($project->openedVersion, '4.1', '>') and $products) ? true : false;

        $title      = $project->name . $this->lang->colon . $this->lang->tree->manageProject;
        $position[] = html::a($this->createLink('project', 'task', "projectID=$rootID"), $project->name);
        $position[] = $this->lang->tree->manageProject;

        $this->view->title           = $title;
        $this->view->position        = $position;
        $this->view->rootID          = $rootID;
        $this->view->productID       = $productID;
        $this->view->allProject      = $projects;
        $this->view->newModule       = $newModule;
        $this->view->currentProject  = $currentProject;
        $this->view->projectModules  = $this->tree->getTaskOptionMenu($currentProject, $productID);
        $this->view->modules         = $this->tree->getTaskTreeMenu($rootID, $productID, $rooteModuleID = 0, array('treeModel', 'createTaskManageLink'));
        $this->view->sons            = $this->tree->getTaskSons($rootID, $productID, $currentModuleID);
        $this->view->currentModuleID = $currentModuleID;
        $this->view->parentModules   = $parentModules;
        $this->display();
    }

    /**
     * Edit a module.
     * 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function edit($moduleID, $type)
    {
        if(!empty($_POST))
        {
            $this->tree->update($moduleID);
            echo js::alert($this->lang->tree->successSave);
            die(js::reload('parent'));
        }

        $module = $this->tree->getById($moduleID);
        if($module->owner == null and $module->root != 0 and $module->type != 'task')
        {
            $module->owner = $this->loadModel('product')->getById($module->root)->QD;
        }

        if($type == 'task')
        {
            $optionMenu = $this->tree->getTaskOptionMenu($module->root);
            $this->view->optionMenu = $optionMenu;
        }
        else
        {
            $this->view->optionMenu = $this->tree->getOptionMenu($module->root, $module->type);
        }

        $this->view->module = $module;
        $this->view->type   = $type;
        $this->view->users  = $this->loadModel('user')->getPairs('noclosed|nodeleted', $module->owner);

        /* Remove self and childs from the $optionMenu. Because it's parent can't be self or childs. */
        $childs = $this->tree->getAllChildId($moduleID);
        foreach($childs as $childModuleID) unset($this->view->optionMenu[$childModuleID]);

        die($this->display());
    }

    /**
     * Fix path, grades.
     * 
     * @param  string    $root 
     * @param  string    $type 
     * @access public
     * @return void
     */
    public function fix($root, $type)
    {
        $this->tree->fixModulePath($root, $type);
        die(js::alert($this->lang->tree->successFixed) . js::reload('parent'));
    }

    /**
     * Update modules' orders.
     * 
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        if(!empty($_POST))
        {
            $this->tree->updateOrder($_POST['orders']);
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
            $this->tree->manageChild($rootID, $viewType, $_POST['parentModuleID'], $_POST['modules']);
            die(js::reload('parent'));
        }
    }

    /**
     * Delete a module.
     * 
     * @param  int    $rootID 
     * @param  int    $moduleID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($rootID, $moduleID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->tree->confirmDelete, $this->createLink('tree', 'delete', "rootID=$rootID&moduleID=$moduleID&confirm=yes")));
        }
        else
        {
            $result = $this->tree->delete($moduleID);
            if(!$result) die();

            die(js::reload('parent'));
        }
    }

    /**
     * AJAX: Get the option menu of modules.
     * 
     * @param  int    $rootID 
     * @param  string $viewType 
     * @param  int    $rootModuleID 
     * @param  string $returnType
     * @param  bool   $needManage
     * @access public
     * @return string the html select string.
     */
    public function ajaxGetOptionMenu($rootID, $viewType = 'story', $rootModuleID = 0, $returnType = 'html', $needManage = false)
    {
        if($viewType == 'task')
        {
            $optionMenu = $this->tree->getTaskOptionMenu($rootID); 
        }
        else
        {
            $optionMenu = $this->tree->getOptionMenu($rootID, $viewType, $rootModuleID);
        }
        if($returnType == 'html')
        {
            $output = html::select("module", $optionMenu, '', "onchange='loadModuleRelated()' class='form-control'");
            if(count($optionMenu) == 1 and $needManage)
            {
                $output .=  "<span class='input-group-addon'>";
                $output .= html::a($this->createLink('tree', 'browse', "rootID=$rootID&view=$viewType"), $this->lang->tree->manage, '_blank');
                $output .= '&nbsp; ';
                $output .= html::a("javascript:loadProductModules($rootID)", $this->lang->refresh);
                $output .= '</span>';
            }
            die($output);
        }
        if($returnType == 'json') die(json_encode($optionMenu));
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
        if($moduleID) die(json_encode($this->dao->findByParent($moduleID)->from(TABLE_MODULE)->fetchPairs('id', 'name')));
        $modules = $this->dao->select('id, name')->from(TABLE_MODULE)
            ->where('root')->eq($rootID)
            ->andWhere('parent')->eq('0')
            ->andWhere('type')->eq($type)
            ->fetchPairs();
        foreach($modules as $key => $name) $modules[$key] = str_replace(" ","&nbsp;","$name");
        die(json_encode($modules));
    }
}

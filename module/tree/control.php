<?php
/**
 * The control file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class tree extends control
{
    const NEW_CHILD_COUNT = 5;

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
        elseif($viewType == 'task')
        {
            $project = $this->loadModel('project')->getById($rootID);
            $this->view->root = $project;
            $this->view->projectModules = $this->tree->getOptionMenu($rootID, 'task');
        }
        /* The viewType is doc. */
        elseif(strpos($viewType, 'doc') !== false)
        {
            $this->loadModel('doc');
            if($rootID == 'product' or $rootID == 'project')
            {
                $viewType  = $rootID . 'doc';
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

            $header['title'] = $this->lang->tree->manageProduct . $this->lang->colon . $product->name;
            $position[]      = html::a($this->createLink('product', 'browse', "product=$rootID"), $product->name);
            $position[]      = $this->lang->tree->manageProduct;
        }
        elseif($viewType == 'task')
        {
            $this->lang->set('menugroup.tree', 'project');
            $this->project->setMenu($this->project->getPairs(), $rootID, 'task');
            $this->lang->tree->menu      = $this->lang->project->menu;
            $this->lang->tree->menuOrder = $this->lang->project->menuOrder;

            $projects = $this->project->getPairs();
            unset($projects[$rootID]);
            $currentProject = key($projects);

            $this->view->allProject     = $projects;
            $this->view->currentProject = $currentProject;
            $this->view->projectModules = $this->tree->getOptionMenu($currentProject, 'task');

            $header['title'] = $this->lang->tree->manageProject . $this->lang->colon . $project->name;
            $position[]      = html::a($this->createLink('project', 'task', "projectID=$rootID"), $project->name);
            $position[]      = $this->lang->tree->manageProject;
        }
        elseif($viewType == 'bug')
        {
            $this->loadModel('bug')->setMenu($this->product->getPairs(), $rootID);
            $this->lang->tree->menu      = $this->lang->bug->menu;
            $this->lang->tree->menuOrder = $this->lang->bug->menuOrder;
            $this->lang->set('menugroup.tree', 'qa');

            $header['title'] = $this->lang->tree->manageBug . $this->lang->colon . $product->name;
            $position[]      = html::a($this->createLink('bug', 'browse', "product=$rootID"), $product->name);
            $position[]      = $this->lang->tree->manageBug;
        }
        elseif($viewType == 'case')
        {
            $this->loadModel('testcase')->setMenu($this->product->getPairs(), $rootID);
            $this->lang->tree->menu      = $this->lang->testcase->menu;
            $this->lang->tree->menuOrder = $this->lang->testcase->menuOrder;
            $this->lang->set('menugroup.tree', 'qa');

            $header['title'] = $this->lang->tree->manageCase . $this->lang->colon . $product->name;
            $position[]      = html::a($this->createLink('testcase', 'browse', "product=$rootID"), $product->name);
            $position[]      = $this->lang->tree->manageCase;
        }
        elseif(strpos($viewType, 'doc') !== false)
        {
            $this->doc->setMenu($this->doc->getLibs(), $rootID, 'doc');
            $this->lang->tree->menu      = $this->lang->doc->menu;
            $this->lang->tree->menuOrder = $this->lang->doc->menuOrder;
            $this->lang->set('menugroup.tree', 'doc');

            $header['title'] = $this->lang->tree->manageCustomDoc . $this->lang->colon . $lib->name;
            $position[]      = html::a($this->createLink('doc', 'browse', "libID=$rootID"), $lib->name);
            $position[]      = $this->lang->tree->manageCustomDoc;
        }

        $parentModules = $this->tree->getParents($currentModuleID);
        $this->view->header          = $header;
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
     * Edit a module.
     * 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function edit($moduleID)
    {
        if(!empty($_POST))
        {
            $this->tree->update($moduleID);
            echo js::alert($this->lang->tree->successSave);
            die(js::reload('parent'));
        }
        $module = $this->tree->getById($moduleID);
        if($module->owner == null)
        {
           $module->owner = $this->loadModel('product')->getById($module->root)->QM;
        }
        $this->view->module     = $module;
        $this->view->optionMenu = $this->tree->getOptionMenu($this->view->module->root, $this->view->module->type);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');

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
            echo js::confirm($this->lang->tree->confirmDelete, $this->createLink('tree', 'delete', "rootID=$rootID&moduleID=$moduleID&confirm=yes"));
            exit;
        }
        else
        {
            $this->tree->delete($moduleID);
            die(js::reload('parent'));
        }
    }

    /**
     * AJAX: Get the option menu of modules.
     * 
     * @param  int    $rootID 
     * @param  string $viewType 
     * @param  int    $rootModuleID 
     * @access public
     * @return string the html select string.
     */
    public function ajaxGetOptionMenu($rootID, $viewType = 'story', $rootModuleID = 0, $returnType = 'html')
    {

        $this->view->productModules = $this->tree->getOptionMenu($rootID, 'story');
        $optionMenu = $this->tree->getOptionMenu($rootID, $viewType, $rootModuleID);
        if($returnType == 'html') die( html::select("module", $optionMenu, '', 'onchange=setAssignedTo()'));
        if($returnType == 'json') die(json_encode($optionMenu));
    }

    /**
     * AJAX: get a module's son modules.
     * 
     * @param  int $moduleID 
     * @param  int $rootID 
     * @access public
     * @return string json_encoded modules.
     */
    public function ajaxGetSonModules($moduleID, $rootID = 0)
    {
        if($moduleID) die(json_encode($this->dao->findByParent($moduleID)->from(TABLE_MODULE)->fetchPairs('id', 'name')));
        $modules = $this->dao->select('id, name')->from(TABLE_MODULE)
            ->where('root')->eq($rootID)
            ->andWhere('parent')->eq('0')
            ->andWhere('type')->eq('story')
            ->fetchPairs();
        foreach($modules as $key => $name) $modules[$key] = str_replace(" ","&nbsp;","$name");
        die(json_encode($modules));
    }
}

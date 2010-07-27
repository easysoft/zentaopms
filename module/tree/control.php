<?php
/**
 * The control file of tree module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
class tree extends control
{
    protected $product;
    const NEW_CHILD_COUNT = 5;

    /* 构造函数，加载产品模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('bug');
        $this->loadModel('testcase');
    }

    /* 设置当前的产品。*/
    private function setProduct($productID)
    {
        $this->product = $this->product->getById($productID);
    }

    /* 模块列表。*/
    public function browse($productID, $viewType, $currentModuleID = 0)
    {
        $product = $this->product->getById($productID);

        if($viewType == 'story')
        {
            /* 设置菜单。*/
            $this->product->setMenu($this->product->getPairs(), $productID, 'product');
            $this->lang->tree->menu = $this->lang->product->menu;
            $this->lang->set('menugroup.tree', 'product');

            /* 设置导航。*/
            $header['title'] = $this->lang->tree->manageProduct . $this->lang->colon . $product->name;
            $position[]      = html::a($this->createLink('product', 'browse', "product=$productID"), $product->name);
            $position[]      = $this->lang->tree->manageProduct;
        }
        elseif($viewType == 'bug')
        {
            /* 设置菜单。*/
            $this->bug->setMenu($this->product->getPairs(), $productID);
            $this->lang->tree->menu = $this->lang->bug->menu;
            $this->lang->set('menugroup.tree', 'qa');

            $header['title'] = $this->lang->tree->manageBug . $this->lang->colon . $product->name;
            $position[]      = html::a($this->createLink('bug', 'browse', "product=$productID"), $product->name);
            $position[]      = $this->lang->tree->manageBug;
            $this->lang->set('menugroup.tree', 'qa');
        }
        elseif($viewType == 'case')
        {
            /* 设置菜单。*/
            $this->testcase->setMenu($this->product->getPairs(), $productID);
            $this->lang->tree->menu = $this->lang->testcase->menu;
            $this->lang->set('menugroup.tree', 'qa');

            $header['title'] = $this->lang->tree->manageCase . $this->lang->colon . $product->name;
            $position[]      = html::a($this->createLink('testcase', 'browse', "product=$productID"), $product->name);
            $position[]      = $this->lang->tree->manageCase;
            $this->lang->set('menugroup.tree', 'qa');
        }

        $parentModules = $this->tree->getParents($currentModuleID);
        $this->view->header          = $header;
        $this->view->position        = $position;
        $this->view->productID       = $productID;
        $this->view->product         = $product;
        $this->view->viewType        = $viewType;
        $this->view->modules         = $this->tree->getTreeMenu($productID, $viewType, $rooteModuleID = 0, array('treeModel', 'createManageLink'));
        $this->view->productModules  = $this->tree->getOptionMenu($productID, 'story');
        $this->view->sons            = $this->tree->getSons($productID, $currentModuleID, $viewType);
        $this->view->currentModuleID = $currentModuleID;
        $this->view->parentModules   = $parentModules;
        $this->display();
    }

    /* 编辑模块。*/
    public function edit($moduleID)
    {
        if(!empty($_POST))
        {
            $this->tree->update($moduleID);
            echo js::alert($this->lang->tree->successSave);
            die(js::reload('parent'));
        }
        $this->view->module     = $this->tree->getById($moduleID);
        $this->view->optionMenu = $this->tree->getOptionMenu($this->view->module->root, $this->view->module->type);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');

        /* 去掉自己和child。*/
        $childs = $this->tree->getAllChildId($moduleID);
        foreach($childs as $childModuleID) unset($this->view->optionMenu[$childModuleID]);

        die($this->display());
    }

    /* 更新排序。*/
    public function updateOrder()
    {
        if(!empty($_POST))
        {
            $this->tree->updateOrder($_POST['orders']);
            die(js::reload('parent'));
        }
    }

    /* 维护子菜单。*/
    public function manageChild($productID, $viewType)
    {
        if(!empty($_POST))
        {
            $this->tree->manageChild($productID, $viewType, $_POST['parentModuleID'], $_POST['modules']);
            die(js::reload('parent'));
        }
    }

    /* 删除某一个模块。*/
    public function delete($productID, $moduleID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->tree->confirmDelete, $this->createLink('tree', 'delete', "productID=$productID&moduleID=$moduleID&confirm=yes"));
            exit;
        }
        else
        {
            $this->tree->delete($moduleID);
            die(js::reload('parent'));
        }
    }

    /* ajax请求： 返回某一个产品的模块列表。*/
    public function ajaxGetOptionMenu($productID, $viewType = 'product', $rootModuleID = 0)
    {
        $optionMenu = $this->tree->getOptionMenu($productID, $viewType, $rootModuleID);
        die( html::select("module", $optionMenu, '', 'onchange=setAssignedTo()'));
    }

    /* ajax请求： 返回某一个模块的son模块.*/
    public function ajaxGetSonModules($moduleID, $productID = 0)
    {
        if($moduleID) die(json_encode($this->dao->findByParent($moduleID)->from(TABLE_MODULE)->fetchPairs('id', 'name')));
        $modules = $this->dao->select('id, name')->from(TABLE_MODULE)
            ->where('root')->eq($productID)
            ->andWhere('parent')->eq('0')
            ->andWhere('type')->eq('story')
            ->fetchPairs();
        die(json_encode($modules));
    }
}

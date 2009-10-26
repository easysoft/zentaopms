<?php
/**
 * The control file of case currentModule of ZenTaoMS.
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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class testcase extends control
{
    private $products = array();

    /* 构造函数，加载story, release, tree等模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->products = $this->product->getPairs();
        $this->assign('products', $this->products);
    }

    /* case首页。*/
    public function index()
    {
        $this->locate($this->createLink('testcase', 'browse'));
    }

    /* 浏览一个产品下面的case。*/
    public function browse($productID = 0, $type = 'byModule', $param = 0)
    {
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        $productID = (int)$productID;
        if($productID == 0) $productID = key($this->products);

        $currentModuleID = ($type == 'byModule') ? (int)$param : 0;
        if($currentModuleID == 0)
        {
            $currentModuleName = $this->lang->case->allCases;
        }
        else
        {
            $currentModule = $this->tree->getById($currentModuleID);
            $currentModuleName = sprintf($this->lang->case->moduleCases, $currentModule->name);
        }

        if($type == "byModule")
        {
            $childModuleIds = $this->tree->getAllChildId($currentModuleID);
            $cases = $this->testcase->getModuleCases($productID, $childModuleIds);
        }
        
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->case->common;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->case->common;

        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleTree',    $this->tree->getTreeMenu($productID, $viewType = 'case', $rooteModuleID = 0, array('treeModel', 'createCaseLink')));
        $this->assign('type',          $type);
        $this->assign('cases',         $cases);
        $this->assign('currentModuleID', $currentModuleID);
        $this->assign('currentModuleName', $currentModuleName);

        $this->display();
    }

    /* 创建case。*/
    public function create($productID, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $_POST['pri'] = str_replace('item', '', $_POST['pri']);
            $this->testcase->create();
            die(js::locate($this->createLink('testcase', 'browse', "productID=$_POST[productID]&type=byModule&param=$_POST[moduleID]"), 'parent'));
        }

        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        $productID = (int)$productID;
        if($productID == 0) $productID = key($this->products);
        $currentModuleID = (int)$moduleID;

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->case->create;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->case->create;

        $users = array('' => '') + $this->user->getPairs($this->app->company->id);
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleOptionMenu',  $this->tree->getOptionMenu($productID, $viewType = 'case', $rooteModuleID = 0));
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('users',  $users);           

        $this->display();
    }

    /* 查看一个case。*/
    public function view($caseID)
    {
        $case = $this->testcase->getById($caseID);
        $productID = $case->product;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->case->view;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->case->view;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('case',      $case);

        $this->display();
    }

    /* 编辑一个Bug。*/
    public function edit($caseID)
    {
        /* 更新case信息。*/
        if(!empty($_POST))
        {
            $this->testcase->update($caseID);
            die(js::locate($this->createLink('testcase', 'view', "caseID=$caseID"), 'parent'));
        }

        /* 生成表单。*/
        $case            = $this->testcase->getById($caseID);
        $productID       = $case->product;
        $currentModuleID = $case->module;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->case->edit;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->case->edit;

        $users = array('' => '') + $this->user->getPairs($this->app->company->id);
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleOptionMenu',  $this->tree->getOptionMenu($productID, $viewType = 'case', $rooteModuleID = 0));
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('users',  $users);           

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('case',      $case);

        $this->display();
    }

    public function delete($id)
    {
        $header['title'] = $this->lang->page->delete;
        $this->assign('header', $header);
        $this->display();
    }
}

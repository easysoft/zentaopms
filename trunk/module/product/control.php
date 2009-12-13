<?php
/**
 * The control file of product module of ZenTaoMS.
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
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class product extends control
{
    private $products = array();

    /* 构造函数，加载story, release, tree等模块。*/
    public function __construct()
    {
        parent::__construct();

        /* 加载需要的模块。*/
        $this->loadModel('story');
        $this->loadModel('release');
        $this->loadModel('tree');
        $this->loadModel('user');

        /* 获取所有的产品列表。如果还没有产品，则跳转到产品的添加页面。*/
        $this->products = $this->product->getPairs();
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));
        $this->assign('products', $this->products);


    }

    /* 产品视图首页。*/
    public function index()
    {
        $this->locate($this->createLink($this->moduleName, 'browse'));
    }

    /* 浏览某一个产品。*/
    public function browse($productID = 0, $moduleID = 0, $orderBy = 'id|desc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {

        $this->lang->product->menu[]  = html::select('product', $this->products, $productID). $this->lang->arrow;
        $this->lang->product->menu[] = "需求列表|product|browse|productID=$productID";
        $this->lang->product->menu[] = '新增产品|product|create||right|';

        /* 设置当前的产品id和模块id。*/
        $this->session->set('storyList', $this->app->getURI(true));
        $productID      = common::saveProductState($productID, key($this->products));
        $moduleID       = (int)$moduleID;
        $childModuleIds = $this->tree->getAllChildID($moduleID);

        /* 设置header和导航条信息。*/
        $header['title'] = $this->lang->product->index . $this->lang->colon . $this->products[$productID];
        $position[]      = $this->products[$productID];

        /* 加载分页类，并查询stories列表。*/
        $this->app->loadClass('pager', $static = true);
        $pager   = new pager($recTotal, $recPerPage, $pageID);
        $stories = $this->story->getProductStories($productID, $childModuleIds, 'all', $orderBy, $pager);

        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleID',      $moduleID);
        $this->assign('stories',       $stories);
        $this->assign('moduleTree',    $this->tree->getTreeMenu($productID, $viewType = 'product', $rooteModuleID = 0, array('treeModel', 'createStoryLink')));
        $this->assign('parentModules', $this->tree->getParents($moduleID));
        $this->assign('pager',         $pager->get());
        $this->assign('recTotal',      $pager->recTotal);
        $this->assign('recPerPage',    $pager->recPerPage);
        $this->assign('users',         $this->user->getPairs($this->app->company->id, 'noletter'));
        $this->assign('orderBy',       $orderBy);

        $this->display();
    }

    /* 新增产品。*/
    public function create()
    {
        if(!empty($_POST))
        {
            $productID = $this->product->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink($this->moduleName, 'browse', "productID=$productID"), 'parent'));
        }

        $header['title'] = $this->lang->product->create;
        $position[]      = $header['title'];
        $this->assign('header', $header);
        $this->assign('position', $position);
        $this->display();
    }

    /* 编辑产品。*/
    public function edit($productID)
    {
        if(!empty($_POST))
        {
            $this->product->update($productID); 
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('product', 'browse', "product=$productID"), 'parent'));
        }

        $product = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
        $header['title'] = $this->lang->product->edit . $this->lang->colon . $product->name;
        $position[]      = html::a($this->createLink($this->moduleName, 'browse'), $product->name);
        $position[]      = $this->lang->product->edit;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('product',  $product);

        $this->display();
    }

    /* 删除产品。*/
    public function delete($productID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->product->confirmDelete, $this->createLink('product', 'delete', "productID=$productID&confirm=yes"));
            exit;
        }
        else
        {
            $this->product->delete($productID);
            echo js::locate($this->createLink('product', 'browse'), 'parent');
            exit;
        }
    }

    /* 获得某一个产品对应的项目列表。*/
    public function ajaxGetProjects($productID, $projectID = 0)
    {
        $projects = $this->product->getProjectPairs($productID);
        die(html::select('project', $projects, $projectID, 'onchange=loadProjectStoriesAndTasks(this.value)'));
    }
}

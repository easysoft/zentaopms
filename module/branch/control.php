<?php
/**
 * The control file of branch of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class branch extends control
{
    /**
     * Manage branch.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function manage($productID, $browseType = 'active', $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('product')->setMenu($productID);

        $branchList = $this->branch->getList($productID, $browseType, $orderBy, $pager = null);

        /* Load pager. */
        $recTotal = count($branchList);
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $branchList = array_chunk($branchList, $pager->recPerPage);

        $this->view->title      = $this->lang->branch->manage;
        $this->view->branchList = empty($branchList) ? $branchList : $branchList[$pageID - 1];
        $this->view->productID  = $productID;
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Create a branch.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function create($productID)
    {
        if($_POST)
        {
            $branchID = $this->branch->create($productID);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action')->create('branch', $branchID, 'Opened');
            die(js::reload('parent.parent'));
        }

        $this->display();
    }

    /**
     * Edit a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function edit($branchID)
    {
        if($_POST)
        {
            $changes = $this->branch->update($branchID);
            if(dao::isError()) die(js::error(dao::getError()));

            if($changes) $this->loadModel('action')->create('branch', $branchID, 'Edited');
            die(js::reload('parent.parent'));
        }

        $this->view->branch = $this->branch->getById($branchID, 0, '');
        $this->display();
    }

    /**
     * Close a branch.
     *
     * @param  int    $branchID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function close($branchID, $confirm = 'no')
    {
        $this->app->loadLang('product');
        $productType = $this->branch->getProductType($branchID);

        if($confirm == 'no')
        {
            die(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmClose), inlink('close', "branchID=$branchID&confirm=yes")));
        }

        $this->branch->close($branchID);
        if(dao::isError()) die(js::error(dao::getError()));

        $this->loadModel('action')->create('branch', $branchID, 'Closed');
        die(js::reload('parent'));
    }

    /**
     * Activate a branch.
     *
     * @param  int    $branchID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function activate($branchID, $confirm = 'no')
    {
        $this->app->loadLang('product');
        $productType = $this->branch->getProductType($branchID);

        if($confirm == 'no')
        {
            die(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmActivate), inlink('activate', "branchID=$branchID&confirm=yes")));
        }

        $this->branch->activate($branchID);
        if(dao::isError()) die(js::error(dao::getError()));

        $this->loadModel('action')->create('branch', $branchID, 'Activated');
        die(js::reload('parent'));
    }

    /**
     * Sort branch.
     *
     * @access public
     * @return void
     */
    public function sort()
    {
        $this->branch->sort();
    }

    /**
     * Ajax get drop menu.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($productID, $branch = 0, $module, $method, $extra = '')
    {
        $branches   = $this->branch->getPairs($productID, 'all');
        $statusList = $this->dao->select('id,status')->from(TABLE_BRANCH)->where('product')->eq($productID)->fetchPairs();

        $this->view->link            = $this->loadModel('product')->getProductLink($module, $method, $extra, true);
        $this->view->productID       = $productID;
        $this->view->projectID       = $this->session->project;
        $this->view->module          = $module;
        $this->view->method          = $method;
        $this->view->extra           = $extra;
        $this->view->branches        = $branches;
        $this->view->currentBranchID = $branch;
        $this->view->branchesPinyin  = common::convert2Pinyin($branches);
        $this->view->statusList      = $statusList;
        $this->display();
    }

    /**
     * Delete branch
     *
     * @param  int    $branchID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($branchID, $confirm = 'no')
    {
        $this->app->loadLang('product');
        $productType = $this->branch->getProductType($branchID);
        if(!$this->branch->checkBranchData($branchID)) die(js::alert(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->canNotDelete)));
        if($confirm == 'no')
        {
            die(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmDelete), inlink('delete', "branchID=$branchID&confirm=yes")));
        }

        $this->branch->delete(TABLE_BRANCH, $branchID);
        die(js::reload('parent'));
    }

    /**
     * Ajax get branches.
     *
     * @param  int    $productID
     * @param  int    $oldBranch
     * @access public
     * @return void
     */
    public function ajaxGetBranches($productID, $oldBranch = 0)
    {
        $product = $this->loadModel('product')->getById($productID);
        if(empty($product) or $product->type == 'normal') die();

        $branches = $this->branch->getPairs($productID);
        if($oldBranch) $branches = array($oldBranch => $branches[$oldBranch]);
        die(html::select('branch', $branches, '', "class='form-control' onchange='loadBranch(this)'"));
    }
}

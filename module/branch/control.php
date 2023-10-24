<?php
/**
 * The control file of branch of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
    public function manage($productID, $browseType = 'active', $orderBy = 'order', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $product = $this->loadModel('product')->getById($productID);
        if($product->type == 'normal') $this->locate($this->createLink('product', 'view', "productID=$productID"));

        $this->product->setMenu($productID);
        $this->session->set('branchManage', $this->app->getURI(true), 'product');
        $this->branch->changeBranchLanguage($productID);

        $branchList = $this->branch->getList($productID, 0, $browseType, $orderBy);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $recTotal   = count($branchList);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $branchList = array_chunk($branchList, $pager->recPerPage);

        $this->view->title       = $this->lang->branch->manage;
        $this->view->branchList  = empty($branchList) ? $branchList : $branchList[$pageID - 1];
        $this->view->productID   = $productID;
        $this->view->browseType  = $browseType;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->view->product     = $this->product->getById($productID);
        $this->view->branchPairs = $this->branch->getPairs($productID, 'active');

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
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->loadModel('action')->create('branch', $branchID, 'Opened');
            return print(js::reload('parent.parent'));
        }

        $this->view->product = $this->loadModel('product')->getById($productID);
        $this->display();
    }

    /**
     * Edit a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function edit($branchID, $productID)
    {
        if($_POST)
        {
            $changes = $this->branch->update($branchID);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($changes) $this->loadModel('action')->create('branch', $branchID, 'Edited');
            return print(js::reload('parent.parent'));
        }

        $this->view->product = $this->loadModel('product')->getById($productID);
        $this->view->branch  = $this->branch->getById($branchID, 0, '');
        $this->display();
    }

    /**
     * Batch edit branch.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function batchEdit($productID)
    {
        $this->loadModel('action');
        $this->loadModel('product')->setMenu($productID);

        if($this->post->IDList)
        {
            $changes = $this->branch->batchUpdate($productID);
            foreach($changes as $branchID => $change)
            {
                $extra = $branchID == BRANCH_MAIN ? $productID : '';
                if($change) $this->action->create('branch', $branchID, 'Edited', '', $extra);
            }

            return print(js::locate($this->session->branchManage, 'parent'));
        }

        $branchList   = $this->branch->getList($productID, 0, 'all');
        $branchIDList = $this->post->branchIDList;
        if(empty($branchIDList)) return print(js::locate($this->session->branchManage, 'parent'));

        foreach($branchList as $branch)
        {
            if(!in_array($branch->id, $branchIDList)) unset($branchList[$branch->id]);
        }

        $this->view->product    = $this->product->getById($productID);
        $this->view->branchList = $branchList;
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
            return print(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmClose), inlink('close', "branchID=$branchID&confirm=yes")));
        }

        $this->branch->close($branchID);
        if(dao::isError()) return print(js::error(dao::getError()));

        $this->loadModel('action')->create('branch', $branchID, 'Closed');
        return print(js::reload('parent'));
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
            return print(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmActivate), inlink('activate', "branchID=$branchID&confirm=yes")));
        }

        $this->branch->activate($branchID);
        if(dao::isError()) return print(js::error(dao::getError()));

        $this->loadModel('action')->create('branch', $branchID, 'Activated');
        return print(js::reload('parent'));
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
    public function ajaxGetDropMenu($productID, $branch, $module, $method, $extra = '')
    {
        parse_str($extra, $output);
        $isQaModule = (strpos(',project,execution,', ",{$this->app->tab},") !== false and strpos(',bug,testcase,groupCase,zeroCase,', ",$method,") !== false and !empty($productID)) ? true : false;
        $param      = $isQaModule ? $extra : 0;
        $param      = isset($output['projectID']) ? $output['projectID'] : $param;
        $branches   = $this->branch->getPairs($productID, 'all', $param);
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
        if(!$this->branch->checkBranchData($branchID)) return print(js::alert(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->canNotDelete)));
        if($confirm == 'no')
        {
            return print(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmDelete), inlink('delete', "branchID=$branchID&confirm=yes")));
        }

        $this->branch->delete(TABLE_BRANCH, $branchID);
        return print(js::reload('parent'));
    }

    /**
     * Ajax get branches.
     *
     * @param  int    $productID
     * @param  int    $oldBranch
     * @param  string $browseType
     * @param  int    $projectID
     * @param  bool   $withMainBranch
     * @param  string $isTwins
     * @param  string $fieldID
     * @param  string $multiple
     * @access public
     * @return void
     */
    public function ajaxGetBranches($productID, $oldBranch = 0, $browseType = 'all', $projectID = 0, $withMainBranch = true, $isTwins = 'no', $fieldID = '0', $multiple = '')
    {
        $product = $this->loadModel('product')->getById($productID);
        if(empty($product) or $product->type == 'normal') return;

        $branches = $this->loadModel('branch')->getList($productID, $projectID, $browseType, 'order', null, $withMainBranch);
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }
        if(is_numeric($oldBranch) and !isset($branchTagOption[$oldBranch]))
        {
            $branch = $this->branch->getById($oldBranch, $productID, '');
            $branchTagOption[$oldBranch] = $oldBranch == BRANCH_MAIN ? $branch : ($branch->name . ($branch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : ''));
        }

        $name = $multiple == 'multiple' ? 'branch[]' : 'branch';

        if($isTwins == 'yes') return print(html::select("branches[$fieldID]", $branchTagOption, $oldBranch, "onchange='loadBranchRelation(this.value, $fieldID);' class='form-control chosen control-branch'"));
        return print(html::select($name, $branchTagOption, $oldBranch, "class='form-control' $multiple onchange='loadBranch(this)' data-last='{$oldBranch}'"));
    }

    /**
     * Set default branch.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $confirm    yes|no
     * @access public
     * @return void
     */
    public function setDefault($productID, $branchID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $this->app->loadLang('product');
            $productType = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch('type');
            return print(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmSetDefault), inlink('setDefault', "productID=$productID&branchID=$branchID&confirm=yes")));
        }

        $this->branch->setDefault($productID, $branchID);

        $this->loadModel('action')->create('branch', $branchID, 'SetDefaultBranch', '', $productID);

        return print(js::reload('parent'));
    }

    /**
     * Merge multiple branches into one branch.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function mergeBranch($productID)
    {
        /* Filter out the main branch and target branch. */
        $mergedBranches = array_filter($_POST['mergedBranchIDList'], function($branch)
        {
            $mergeToBranch  = $_POST['createBranch'] ? '' : $_POST['targetBranch'];
            return $branch != 0 and $branch != $mergeToBranch;
        });

        $mergedBranchIDList = implode(',', $mergedBranches);
        $mergedBranches     = $this->dao->select('id,name')->from(TABLE_BRANCH)->where('id')->in($mergedBranchIDList)->fetchPairs();

        $targetBranch = $this->branch->mergeBranch($productID, $mergedBranchIDList);

        $this->loadModel('action')->create('branch', $targetBranch, 'MergedBranch', '', implode(',', $mergedBranches));

        if(dao::isError()) return $this->send(array('message' => dao::getError(), 'result' => 'fail'));

        return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success'));
    }

    /**
     * AJAX: Get target branches for merge branch.
     *
     * @param  int    $productID
     * @param  string $mergedBranches
     * @access public
     * @return string
     */
    public function ajaxGetTargetBranches($productID, $mergedBranches = '')
    {
        $branchPairs = $this->branch->getPairs($productID, 'active', 0, $mergedBranches);
        return print(html::select('targetBranch', $branchPairs, '', "class='form-control chosen'"));
    }
}

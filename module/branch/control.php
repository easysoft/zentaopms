<?php
declare(strict_types=1);
/**
 * The control file of branch of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class branch extends control
{
    /**
     * 管理分支列表。
     * Manage branch list.
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
    public function manage(int $productID, string $browseType = 'active', string $orderBy = 'order', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
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
        $pager      = new pager($recTotal ? $recTotal : count($branchList), $recPerPage, $pageID);
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
     * 创建一个分支。
     * Create a branch.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function create(int $productID)
    {
        $this->branch->changeBranchLanguage($productID);

        if($_POST)
        {
            $branch   = form::data()->get();
            $branchID = $this->branch->create($productID, $branch);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('branch', $branchID, 'Opened');
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->product = $this->loadModel('product')->getById($productID);
        $this->display();
    }

    /**
     * 编辑一个分支。
     * Edit a branch.
     *
     * @param  int    $branchID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function edit(int $branchID, int $productID)
    {
        $this->branch->changeBranchLanguage($productID);
        if($_POST)
        {
            $branch  = form::data()->get();
            $changes = $this->branch->update($branchID, $branch);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->product = $this->loadModel('product')->getById($productID);
        $this->view->branch  = $this->branch->getById((string)$branchID, 0, '');
        $this->display();
    }

    /**
     * 批量编辑分支。
     * Batch edit branch.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function batchEdit(int $productID)
    {
        $this->loadModel('product')->setMenu($productID);
        if($this->post->branchID)
        {
            $branches = form::batchData($this->config->branch->form->batchedit)->get();
            $changes  = $this->branch->batchUpdate($productID, $branches);
            if(dao::isError()) return $this->sendError(dao::getError());

            foreach($changes as $branchID => $change)
            {
                $extra = $branchID == BRANCH_MAIN ? $productID : '';
                if($change) $this->loadModel('action')->create('branch', $branchID, 'Edited', '', $extra);
            }

            return $this->sendSuccess(array('load' => $this->session->branchManage));
        }

        $branchList   = array_values($this->branch->getList($productID, 0, 'all'));
        $branchIDList = $this->post->branchIDList;
        if(empty($branchIDList)) return $this->sendError($this->lang->branch->noData, inLink('manage', "productID=$productID"));

        foreach($branchList as $index => $branch)
        {
            if(!in_array($branch->id, $branchIDList))
            {
                unset($branchList[$index]);
                continue;
            }

            $branchList[$index]->branchID = $branch->id;
            $branchList[$index]->id       = $index + 1;
        }

        $this->view->title      = $this->lang->branch->edit;
        $this->view->product    = $this->product->getById($productID);
        $this->view->branchList = array_values($branchList);
        $this->display();
    }

    /**
     * 关闭分支。
     * Close a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function close(int $branchID)
    {
        $this->branch->close($branchID);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create('branch', $branchID, 'Closed');
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 激活分支。
     * Activate a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function activate(int $branchID)
    {
        $this->branch->activate($branchID);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create('branch', $branchID, 'Activated');
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 分支排序。
     * Sort branch.
     *
     * @access public
     * @return void
     */
    public function sort()
    {
        $postData = form::data()->get();
        $this->branch->sort($postData);
    }

    /**
     * 分支下拉菜单。
     * Ajax get drop menu.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $module
     * @param  string $method
     * @param  string $extra
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu(int $productID, string $branch, string $module, string $method, string $extra = '')
    {
        parse_str($extra, $output);

        $isQaModule = (strpos(',project,execution,', ",{$this->app->tab},") !== false && strpos(',bug,testcase,groupCase,zeroCase,', ",$method,") !== false && !empty($productID)) ? true : false;
        $param      = $isQaModule ? $extra : 0;
        $param      = isset($output['projectID']) ? $output['projectID'] : $param;
        $branches   = $this->branch->getPairs($productID, 'all', $param);
        $statusList = $this->branch->getStatusList($productID);
        $link       = $this->loadModel('product')->getProductLink($module, $method, $extra, true);

        $this->view->link            = $this->branchZen->setParamsForLink($module, $link, (int)$this->session->project, $productID);
        $this->view->productID       = $productID;
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
     * 获取分支下拉列表。
     * Ajax get branches.
     *
     * @param  int    $productID
     * @param  string $oldBranch
     * @param  string $browseType
     * @param  int    $projectID
     * @param  bool   $withMainBranch
     * @param  string $isTwins
     * @param  string $fieldID
     * @param  string $multiple
     * @access public
     * @return void
     */
    public function ajaxGetBranches(int $productID, string $oldBranch = '0', string $browseType = 'all', int $projectID = 0, bool $withMainBranch = true, string $isTwins = 'no', string $fieldID = '0', string $multiple = '')
    {
        $product = $this->loadModel('product')->getByID($productID);
        if(empty($product) || $product->type == 'normal') return false;

        $branches        = $this->branch->getList($productID, $projectID, $browseType, 'order', null, $withMainBranch);
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        if(is_numeric($oldBranch) && !isset($branchTagOption[$oldBranch]))
        {
            $branch = $this->branch->getByID($oldBranch, $productID, '');
            $branchTagOption[$oldBranch] = $oldBranch == BRANCH_MAIN ? $branch : ($branch->name . ($branch->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : ''));
        }

        $items = array();
        foreach($branchTagOption as $id => $name)
        {
            if($id == '') continue;
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }

        if($isTwins == 'yes') return print(html::select("branches[$fieldID]", $branchTagOption, $oldBranch, "onchange='loadBranchRelation(this.value, $fieldID);' class='form-control chosen control-branch'"));

        return print(json_encode($items));
    }

    /**
     * 将多个分支合并到一个分支。
     * Merge multiple branches into one branch.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function mergeBranch(int $productID)
    {
        if($this->post->mergedBranchIDList)
        {
            /* Filter out the main branch and target branch. */
            $mergedBranchIDList = explode(',', $this->post->mergedBranchIDList);
            $mergedBranches     = array_filter($mergedBranchIDList, function($branch)
            {
                $mergeToBranch  = $this->post->createBranch ? '' : $this->post->targetBranch;
                return $branch != 0 and $branch != $mergeToBranch;
            });

            $mergedBranches = $this->branch->getPairsByIdList($mergedBranches);

            $postData     = form::data()->get();
            $branchIdList = implode(',', array_keys($mergedBranches));
            $targetBranch = $this->branch->mergeBranch($productID, $branchIdList, $postData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('branch', $targetBranch, 'MergedBranch', '', implode(',', $mergedBranches));
            if(dao::isError()) return $this->sendError(dao::getError());
        }
        return $this->sendSuccess(array('load' => true, 'closeModel' => true));
    }
}

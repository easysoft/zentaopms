<?php
declare(strict_types=1);
/**
 * The control file of reporeviewflow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     reporeviewflow
 * @link        https://www.zentao.net
 */
class reporeviewflow extends control
{
    /**
     * 浏览审批流程。
     * Browse review flow.
     *
     * @param  int $repoID
     * @param  int $recTotal
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return void
     */
    public function browse(int $repoID, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('ci')->setMenu($repoID);
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $flowList = $this->reporeviewflow->getList($repoID, $pager);
        foreach($flowList as $flow) $flow->desc = strip_tags($flow->desc);

        $this->view->repoID          = $repoID;
        $this->view->title           = $this->lang->reporeviewflow->browse;
        $this->view->flowList        = $flowList;
        $this->view->pager           = $pager;
        $this->view->branchTypePairs = $this->loadModel('repobranchtype')->getBranchTypePairs($repoID);
        $this->display();
    }

    /**
     * 创建审批流程。
     * Create review flow.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function create(int $repoID)
    {
        $this->loadModel('ci')->setMenu($repoID);
        $repo        = $this->loadModel('repo')->getByID($repoID);

        if($_POST)
        {
            $formData = form::data($this->config->reporeviewflow->form->createReviewFlow)->get();

            $formData->definition = $this->reporeviewflowZen->buildDefinition($formData);

            $flowID = $this->reporeviewflow->create($repoID, $formData);
            if($flowID) $this->loadModel('action')->create('review_flow', $flowID, 'created');

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => inLink('browse', "repoID=$repoID")));
        }

        $repoMembers = !empty($repo->members) ? $this->loadModel('user')->getListByAccounts(array_keys($repo->members)) : array();

        $this->app->loadLang('bug');
        $this->view->title       = $this->lang->reporeviewflow->create;
        $this->view->repoID      = $repoID;
        $this->view->repo        = $repo;
        $this->view->repoMembers = !empty($repoMembers) ? array_column($repoMembers, 'realname', 'account') : array();
        $this->display();
    }

    /**
     * 编辑审批流程。
     * Edit review flow.
     *
     * @param  int $repoID
     * @param  int $flowID
     * @access public
     * @return void
     */
    public function edit(int $repoID, int $flowID)
    {
        $this->loadModel('ci')->setMenu($repoID);
        $repo        = $this->loadModel('repo')->getByID($repoID);
        $reviewFlow  = $this->reporeviewflow->getByID($flowID);

        if($_POST)
        {
            $formData = form::data($this->config->reporeviewflow->form->createReviewFlow)->get();

            $formData->definition = $this->reporeviewflowZen->buildDefinition($formData);

            $result = $this->reporeviewflow->update($reviewFlow, $formData);
            if($result) $this->loadModel('action')->create('review_flow', $flowID, 'edited');

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => inLink('browse', "repoID=$repoID")));
        }

        $repoMembers = !empty($repo->members) ? $this->loadModel('user')->getListByAccounts(array_keys($repo->members)) : array();

        $this->app->loadLang('bug');
        $this->view->title       = $this->lang->reporeviewflow->edit;
        $this->view->repoID      = $repoID;
        $this->view->flowID      = $flowID;
        $this->view->repo        = $repo;
        $this->view->reviewFlow  = $reviewFlow;
        $this->view->repoMembers = !empty($repoMembers) ? array_column($repoMembers, 'realname', 'account') : array();
        $this->display();
    }

    /**
     * 修改审批流程状态。
     * Change review flow status.
     *
     * @param  int    $flowID
     * @param  string $status
     * @access public
     * @return void
     */
    public function changeStatus(int $flowID, string $status)
    {
        $this->reporeviewflow->updateStatus($flowID, $status);
        if(dao::isError()) return $this->sendError(dao::getError());

        $this->loadModel('action')->create('review_flow', $flowID, $status . 'reviewflow');
        return $this->sendSuccess(array('message' => $this->lang->reporeviewflow->{$status . 'Success'}, 'load' => true));
    }

    /**
     * 删除审批流程。
     * Delete review flow.
     *
     * @param  int $flowID
     * @access public
     * @return void
     */
    public function delete(int $flowID)
    {
        $this->reporeviewflow->delete(TABLE_REVIEWFLOW, $flowID);
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->sendSuccess(array('message' => $this->lang->deleteSuccess, 'load' => true));
    }
}

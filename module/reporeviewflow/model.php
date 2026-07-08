<?php
declare(strict_types=1);
/**
 * The model file of reporeviewflow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     reporeviewflow
 * @link        https://www.zentao.net
 */
class reporeviewflowModel extends model
{

    /**
     * 获取指定代码库的评审流程。
     * Get review flow.
     *
     * @param  int     $repoID
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getList(int $repoID, ?object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_REVIEWFLOW)
            ->where('deleted')->eq(0)
            ->andWhere('repo')->eq($repoID)
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 创建评审流程。
     * Create review flow.
     *
     * @param  int    $repoID
     * @param  object $data
     * @access public
     * @return int|false
     */
    public function create(int $repoID, object $data): int|false
    {
        $reviewFlow = new stdClass();
        $reviewFlow->repo        = $repoID;
        $reviewFlow->name        = $data->name;
        $reviewFlow->desc        = $data->desc;
        $reviewFlow->definition  = json_encode(zget($data, 'definition', array()));
        $reviewFlow->status      = 'enable';
        $reviewFlow->createdBy   = $this->app->user->account;
        $reviewFlow->createdDate = helper::now();

        $reviewFlow = $this->loadModel('file')->processImgURL($reviewFlow, $this->config->reporeviewflow->editor->create['id'], (string)$this->post->uid);
        $this->dao->insert(TABLE_REVIEWFLOW)->data($reviewFlow)
            ->check('name', 'unique', "`repo` = $repoID and `deleted` = 0")
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;
        $flowID = $this->dao->lastInsertID();
        $this->file->updateObjectID($this->post->uid, $flowID, 'reporeviewflow');

        return $flowID;
    }

    /**
     * 更新评审流程。
     * Update review flow.
     *
     * @param  object $flow
     * @param  object $data
     * @access public
     * @return bool
     */
    public function update(object $flow, object $data): bool
    {
        $reviewFlow = new stdClass();
        $reviewFlow->name       = $data->name;
        $reviewFlow->desc       = $data->desc;
        $reviewFlow->definition = json_encode(zget($data, 'definition', array()));
        $reviewFlow->status     = $flow->status;
        $reviewFlow->editedBy   = $this->app->user->account;
        $reviewFlow->editedDate = helper::now();

        $reviewFlow = $this->loadModel('file')->processImgURL($reviewFlow, $this->config->reporeviewflow->editor->edit['id'], (string)$this->post->uid);
        $this->dao->update(TABLE_REVIEWFLOW)->data($reviewFlow)
            ->where('id')->eq($flow->id)
            ->check('name', 'unique', "`repo` = {$flow->repo} and id != {$flow->id} and `deleted` = 0")
            ->autoCheck()
            ->exec();
        $this->file->updateObjectID($this->post->uid, $flow->id, 'reporeviewflow');
        return !dao::isError();
    }

    /**
     * 根据ID获取评审流程。
     * Get review flow by id.
     *
     * @param  int $reviewFlowID
     * @access public
     * @return object|false
     */
    public function getByID(int $reviewFlowID): object|false
    {
        $reviewFlow = $this->dao->select('*')->from(TABLE_REVIEWFLOW)
            ->where('deleted')->eq(0)
            ->andWhere('id')->eq($reviewFlowID)
            ->fetch();
        if(empty($reviewFlow)) return false;

        $reviewFlow = $this->loadModel('file')->replaceImgURL($reviewFlow, 'desc');

        $reviewFlow->definition = json_decode($reviewFlow->definition);
        return $reviewFlow;
    }

    /**
     * 更新评审流程状态。
     * Update review flow status.
     *
     * @param  int    $reviewFlowID
     * @param  string $status
     * @access public
     * @return bool
     */
    public function updateStatus(int $reviewFlowID, string $status): bool
    {
        $this->dao->update(TABLE_REVIEWFLOW)->set('status')->eq($status)->where('id')->eq($reviewFlowID)->exec();
        return !dao::isError();
    }

    /**
     * 判断按钮是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $reviewFlow
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $reviewFlow, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'enable') return !empty($reviewFlow->status) && $reviewFlow->status == 'disable';
        if($action == 'disable') return !empty($reviewFlow->status) && $reviewFlow->status == 'enable';

        return true;
    }

    /**
     * 根据代码库和分支名获取评审流程。
     * Get review flow by repo and branch name.
     *
     * @param  int    $repoID
     * @param  string $branchName
     *
     * @access public
     * @return array|object
     */
    public function getByBranchName(int $repoID, string $branchName): array|object
    {
        $branchRule = $this->loadModel('repobranchrule')->getRuleByBranchName($repoID, $branchName);
        if(empty($branchRule) || empty($branchRule->reviewFlowID)) return array();

        return $this->fetchByID($branchRule->reviewFlowID);
    }

    /**
     * 获取评审流程键值对。
     * Get review flow pairs.
     *
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getPairs(int $repoID = 0, string $status = ''): array
    {
        return $this->dao->select('id, name')->from(TABLE_REVIEWFLOW)
            ->where('deleted')->eq(0)
            ->beginIF($repoID)->andWhere('repo')->eq($repoID)->fi()
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->fetchPairs();
    }
}

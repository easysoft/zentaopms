<?php
declare(strict_types=1);

/**
 * The model file of ppm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 * @property    gitlabModel $gitlab
 */
class ppmModel extends model
{
    public $moduleName = 'ppm';

    public function __construct($appName = '')
    {
        parent::__construct($appName);
        if($this->app->rawModule == 'pullreq') $this->moduleName = 'pullreq';
    }

    /**
     * 获取合并请求列表.
     * Get MR list of gitlab project.
     *
     * @param  string $mode
     * @param  string $param
     * @param  string $orderBy
     * @param  array  $filterProjects
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $mode = 'all', string $param = 'all', string $orderBy = 'id_desc', array $filterProjects = array(), int $repoID = 0, int $objectID = 0, ?object $pager = null): array
    {
        $filterProjectSql = '';
        if(!$this->app->user->admin && !empty($filterProjects))
        {
            foreach($filterProjects as $repoID)
            {
                $filterProjectSql .= "(sourceRepoID = '{$repoID}') OR ";
            }

            if($filterProjectSql) $filterProjectSql = '(' . substr($filterProjectSql, 0, -3) . ')'; // Remove last or.
        }

        $repoPairs = in_array($this->app->tab, array('project', 'execution')) ? $this->loadModel('repo')->getRepoPairs($this->app->tab, $objectID) : array();
        if($this->app->tab == 'project')
        {
            $executionIdList = $this->loadModel('execution')->fetchExecutionList($objectID, 'all');
            if(!empty($executionIdList)) $objectID = array_merge(array_keys($executionIdList), array($objectID));
        }

        return $this->dao->select('*')->from(TABLE_PPM)
            ->where('1=1')
            ->beginIF($mode == 'status' && $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($mode == 'creator' && $param != 'all')->andWhere('createdBy')->eq($param)->fi()
            ->beginIF($filterProjectSql)->andWhere($filterProjectSql)->fi()
            ->beginIF($repoID)->andWhere('repoID')->eq($repoID)->fi()
            ->beginIF($objectID)->andWhere('executionID')->in($objectID)->fi()
            ->beginIF(!empty($repoPairs))->andWhere('repoID')->in(array_keys($repoPairs))->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 根据代码库ID获取合并请求列表.
     * Get MR list by repoID.
     *
     * @access public
     * @return array
     */
    public function getPairs(int $repoID): array
    {
        return $this->dao->select('id,title')
            ->from(TABLE_PPM)
            ->where('repoID')->eq($repoID)
            ->orderBy('id')
            ->fetchPairs('id', 'title');
    }

    /**
     * 创建合并请求。
     * Create MR function.
     *
     * @param  object $ppm
     * @access public
     * @return int|false
     */
    public function create(object $ppm): int|false
    {
        $result = $this->checkSameOpened($ppm->repoID, $ppm->sourceRepoID, $ppm->sourceBranch, $ppm->targetRepoID, $ppm->targetBranch);
        if($result['result'] == 'fail')
        {
            dao::$errors['message'] = $result['message'];
            return false;
        }

        if(!empty($ppm->reviewer) && !$ppm->approvalflow)
        {
            $reviewers = is_string($ppm->reviewer) ? explode(',', $ppm->reviewer) : $ppm->reviewer;
            if(!empty($ppm->reviewFlowID))
            {
                $flow = $this->loadModel('reporeviewflow')->getByID($ppm->reviewFlowID);
                if(empty($flow))
                {
                    dao::$errors['message'] = $this->lang->ppm->errorLang[10];
                    return false;
                }

                $specifiedReviewers = $flow->definition->reviewFlow->approvals->specifiedReviewers;
                $noHasReviewers     = array_diff($specifiedReviewers, $reviewers);

                if(!empty(array_filter($noHasReviewers)))
                {
                    $noHasReviewers = $this->loadModel('user')->getListByAccounts($noHasReviewers);
                    $noHasReviewers = array_column($noHasReviewers, 'realname');
                    dao::$errors['reviewer'] = sprintf($this->lang->ppm->checkReviewers, implode(',', $noHasReviewers));
                    return false;
                }
            }
        }

        $diffStats = $this->loadModel('gitfox')->apiGetDiffStats((int)$ppm->sourceRepoID, $ppm->sourceBranch, $ppm->targetBranch);
        if(dao::isError()) return false;
        if(!empty($diffStats))
        {
            $ppm->additions   = zget($diffStats, 'additions', 0);
            $ppm->deletions   = zget($diffStats, 'deletions', 0);
            $ppm->commitCount = zget($diffStats, 'commits', 0);
            $ppm->fileCount   = zget($diffStats, 'filesChanged', 0);
        }
        $ppm = $this->loadModel('file')->processImgURL($ppm, $this->config->ppm->editor->create['id'], (string)$this->post->uid);

        $ppm->mergeBaseSHA = $ppm->mergeTargetSHA;
        $ppmID = $this->insertMr($ppm);
        if(dao::isError()) return false;
        $this->file->updateObjectID($this->post->uid, $ppmID, 'ppm');

        $ppm->id = $ppmID;
        if(!empty($reviewers))
        {
            $this->addReviewers($ppm, $reviewers);
            if(dao::isError()) return false;
        }

        $this->loadModel('action')->create($this->moduleName, $ppmID, 'opened');
        if(dao::isError()) return false;

        $this->apiTriggerEvent((int)$ppm->targetRepoID, $ppmID, 'create');
        if(dao::isError()) return false;

        $this->linkObjects($ppm);

        return $ppmID;
    }

    /**
     * 更新合并请求。
     * Edit PPM function.
     *
     * @param  int    $id
     * @param  object $ppm
     * @access public
     * @return array
     */
    public function update(int $id, object $ppm): array
    {
        $oldPPM = $this->fetchByID($id);
        if(!$oldPPM) return array('result' => 'fail', 'message' => $this->lang->ppm->notFound);

        /* Update MR in Zentao database. */
        $this->dao->update(TABLE_PPM)->data($ppm, $this->config->ppm->edit->skippedFields)
            ->where('id')->eq($id)
            ->batchCheck($this->config->ppm->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $ppm = $this->fetchByID($id);
        $this->linkObjects($ppm);

        $actionID = $this->loadModel('action')->create($this->moduleName, $id, 'edited');
        $changes  = common::createChanges($oldPPM, $ppm);
        if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        $this->createMRLinkedAction($id, 'edit' . $this->moduleName, $ppm->editedDate);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        if($this->session->{$this->app->tab}) $ppm->executionID = $this->session->{$this->app->tab};
        $linkParams = $this->app->tab == 'execution' || $this->app->tab == 'project' ? "repoID=0&mode=status&param=opened&objectID={$ppm->executionID}" : "repoID={$ppm->repoID}";
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => helper::createLink($this->moduleName, 'browse', $linkParams));
    }

    /**
     * 更新合并请求关联信息。
     * Update MR linked info.
     *
     * @param  int    $ppmID
     * @param  string $action  createmr|editmr|removemr
     * @param  string $actionDate
     * @access public
     * @return bool
     */
    public function createMRLinkedAction(int $id, string $action, string $actionDate = ''): bool
    {
        if(empty($actionDate)) $actionDate = helper::now();

        $mrAction = $actionDate . '::' . $this->app->user->account . '::' . helper::createLink($this->moduleName, 'view', "id={$id}");

        $this->loadModel('action');
        foreach(array('story', 'task', 'bug') as $objectType)
        {
            $linkedObjects = $this->ppmTao->getLinkedObjectPairs($id, $objectType);
            foreach($linkedObjects as $objectID) $this->action->create($objectType, $objectID, $action, '', $mrAction);
        }
        return !dao::isError();
    }

    /**
     * 通过API获取合并请求的提交信息。
     * Get MR commits by API.
     *
     * @param  int    $hostID
     * @param  string $projectID  targetProject
     * @param  int    $ppmID
     * @param  object $pager
     * @access public
     * @return array|null
     */
    public function apiGetMRCommits(int $targetRepoID, int $ppmID, ?object $pager = null): array|null
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $ppm     = $this->fetchByID($ppmID);
        if(empty($apiRoot)) return array();

        $url = sprintf($apiRoot->url, "/repos/{$targetRepoID}/pullreq/{$ppmID}/commits");
        if(is_null($pager))
        {
            $params = array();
            $params['pageSize'] = 100;

            $commitList = array();
            for($i = 0; true; $i++)
            {
                $params['page'] = $i + 1;
                $apiURL = $url . '?' . http_build_query($params);
                $response = json_decode(commonModel::http($apiURL, null, array(), $apiRoot->header));
                if(empty($response) || empty($response->data)) break;
                $commitList = array_merge($commitList, $response->data);
                if(!empty($response->listArgs) && $response->listArgs->pageSize < 100) break;
            }

            return $commitList;

        }
        else
        {
            $url .= '?' . http_build_query($this->gitfox->getPage($pager));
            $response = json_decode(commonModel::http($url, null, array(), $apiRoot->header));
            $response = $this->gitfox->getResponse($response);
            if(empty($response) || empty($response->data)) return array();
            $pager->recTotal   = $response->pager->total;
            $pager->recPerPage = $response->pager->pageSize;
            $pager->pageID     = $response->pager->page;
            $response = zget($response, 'data', array());
            foreach($response as $commit)
            {
                $commit->id            = substr($commit->sha, 0, 7);
                $commit->repoID        = $ppm->repoID;
                $commit->committedDate = empty($commit->author) ? '' : $commit->author->when;
                $commit->authorName    = empty($commit->author) ? '' : $commit->author->identity->name;
            }
            return $response;
        }
    }

    /**
     * 获取合并请求的对比信息。
     * Get MR diff versions by API.
     *
     * @param  object $ppm
     * @access public
     * @return array
     */
    public function getDiffs(object $ppm): array
    {
        if(!isset($ppm->repoID)) return array();

        $repo = $this->loadModel('repo')->getByID($ppm->repoID);
        if(!$repo) return array();

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $lines = array();
        $lines = preg_replace('/^\s*$\n?\r?/m', '', $ppm->diffs);

        if(is_string($lines)) $lines = explode("\n", $lines);
        return $scm->engine->parseDiff($lines);
    }

    /**
     * 审核合并请求。
     * Reject or Approve this MR.
     *
     * @param  int    $ppmID
     * @param  object $formData
     * @return bool
     */
    public function review(int $ppmID, object $formData, $account = ''): bool
    {
        if(!$account) $account = $this->app->user->account;
        $this->dao->update(TABLE_PPMREVIEWERS)->data($formData)
            ->where('requestID')->eq($ppmID)
            ->andWhere('account')->eq($account)
            ->exec();
        return !dao::isError();
    }

    /**
     * 关闭合并请求。
     * Close this MR.
     *
     * @param  int $ppmID
     * @access public
     * @return bool
     */
    public function close(int $ppmID): bool
    {
        $this->dao->update(TABLE_PPM)->set('status')->eq('closed')->where('id')->eq($ppmID)->exec();

        $ppm = $this->fetchByID($ppmID);
        $this->apiTriggerEvent((int)$ppm->targetRepoID, $ppmID, 'close');
        return !dao::isError();
    }

    /**
     * 重新打开合并请求。
     * Reopen this MR.
     *
     * @param  int $ppmID
     * @access public
     * @return bool
     */
    public function reopen(int $ppmID): bool
    {
        $this->dao->update(TABLE_PPM)->set('status')->eq('opened')->where('id')->eq($ppmID)->exec();

        $ppm = $this->fetchByID($ppmID);
        $this->apiTriggerEvent((int)$ppm->targetRepoID, $ppmID, 'reopen');
        return !dao::isError();
    }

    /**
     * 获取合并请求关联的对象。
     * Get mr link list.
     *
     * @param  int    $ppmID
     * @param  string $type       story|task|bug
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkList(int $ppmID, string $type, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if(!isset($this->config->objectTables[$type])) return array();

        $orderBy = str_replace('name_', 'title_', $orderBy);
        if($type == 'task') $orderBy = str_replace('title_', 'name_', $orderBy);

        return $this->dao->select('t1.*')->from($this->config->objectTables[$type])->alias('t1')
            ->leftJoin(TABLE_RELATION)->alias('t2')->on('t1.id=t2.BID')
            ->where('t2.relation')->eq('interrated')
            ->andWhere('t2.AType')->eq($this->moduleName)
            ->andWhere('t2.AID')->eq($ppmID)
            ->andWhere('t2.BType')->eq($type)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 根据对象信息获取合并请求列表。
     * Get linked MR pairs.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $module      mr|pullreq
     * @access public
     * @return array
     */
    public function getLinkedMRPairs(int $objectID, string $objectType = 'story', string $module = ''): array
    {
        if(!$module) $module = $this->moduleName;
        return $this->dao->select("t2.id,t2.title,t2.status")->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_PPM)->alias('t2')->on('t1.AID = t2.id')
            ->where('t1.AType')->eq($module)
            ->andWhere('t1.BType')->eq($objectType)
            ->andWhere('t1.BID')->eq($objectID)
            ->andWhere('t2.id')->ne(0)
            ->fetchAll('id');
    }

    /**
     * 合并请求关联对象。
     * Create an mr link.
     *
     * @param  int    $ppmID
     * @param  string $type       story|task|bug
     * @param  array  $objects
     * @access public
     * @return bool
     */
    public function link(int $ppmID, string $type, array $objects): bool
    {
        if(!isset($this->config->objectTables[$type])) return false;

        $ppm = $this->fetchByID($ppmID);
        if(!$ppm) return false;

        /* Set link action text. */
        $user    = $this->loadModel('user')->getRealNameAndEmails($ppm->createdBy);
        $comment = $ppm->createdDate . '::' . zget($user, 'realname', $this->app->user->realname) . '::' . helper::createLink($this->moduleName, 'view', "id={$ppm->id}");

        $this->loadModel('action');
        foreach($objects as $objectID)
        {
            $relation = new stdclass();
            $relation->product  = 0;
            $relation->AType    = $this->moduleName;
            $relation->AID      = $ppmID;
            $relation->relation = 'interrated';
            $relation->BType    = $type;
            $relation->BID      = $objectID;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

            $this->action->create($type, (int)$objectID, 'create' . $this->moduleName, '', $comment);
        }

        return !dao::isError();
    }

    /**
     * 保存合并请求关联的对象。
     * Save linked objects.
     *
     * @param  object $ppm
     * @access public
     * @return bool
     */
    public function linkObjects(object $ppm): bool
    {
        /* Get commits by MR. */
        $commits = $this->apiGetMRCommits((int)$ppm->targetRepoID, $ppm->id);
        if(empty($commits)) return true;

        /* Init objects. */
        $objectList = array('story' => array(), 'bug' => array(), 'task' => array());
        $this->loadModel('repo');
        foreach($commits as $commit)
        {
            if(empty($commit->message)) $commit->message = zget($commit, 'title', '');
            $objects = $this->repo->parseComment($commit->message);
            $objectList['story'] = array_merge($objectList['story'], $objects['stories']);
            $objectList['bug']   = array_merge($objectList['bug'],   $objects['bugs']);
            $objectList['task']  = array_merge($objectList['task'],  $objects['tasks']);
        }

        $users          = $this->loadModel('user')->getPairs('noletter');
        $mrCreateAction = $ppm->createdDate . '::' . zget($users, $ppm->createdBy) . '::' . $ppm->id;
        $product        = $this->getMRProduct($ppm);

        $this->loadModel('action');
        foreach($objectList as $type => $objectIDs)
        {
            $relation           = new stdclass();
            $relation->product  = $product ? $product->id : 0;
            $relation->AType    = $this->moduleName;
            $relation->AID      = $ppm->id;
            $relation->relation = 'interrated';
            $relation->BType    = $type;
            foreach($objectIDs as $objectID)
            {
                $relation->BID = $objectID;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                $this->action->create($type, (int)$objectID, 'create' . $this->moduleName, '', $mrCreateAction);
            }
        }
        return !dao::isError();
    }

    /**
     * 解除合并请求关联的对象。
     * Unlink an mr link.
     *
     * @param  int    $ppmID
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function unlink(int $ppmID, string $type, int $objectID): bool
    {
        if(!isset($this->config->objectTables[$type])) return false;

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->eq($this->moduleName)
            ->andWhere('AID')->eq($ppmID)
            ->andWhere('relation')->eq('interrated')
            ->andWhere('BType')->eq($type)
            ->andWhere('BID')->eq($objectID)
            ->exec();

        $this->loadModel('action')->create($type, $objectID, 'deleteppm', '', helper::createLink($this->moduleName, 'view', "id={$ppmID}"));
        return !dao::isError();
    }

    /**
     * 获取合并请求的产品。
     * Get mr product.
     *
     * @param  object $mr
     * @access public
     * @return object|false
     */
    public function getMRProduct(object $mr): object|false
    {
        $productID = $this->dao->select('product')->from(TABLE_REPO)->where('id')->eq($mr->repoID)->fetch('product');
        if(!$productID) return false;

        return $this->loadModel('product')->getById((int)$productID);
    }

    /**
     * 获取合并请求的收件人和抄送人。
     * Get toList and ccList.
     *
     * @param  object $mr
     * @access public
     * @return array
     */
    public function getToAndCcList(object $mr): array
    {
        return array($mr->createdBy, $mr->assignee);
    }

    /**
     * 将合并的操作记录到链接
     * Log merged action to links.
     *
     * @param  object $mr
     * @access public
     * @return bool
     */
    public function logMergedAction(object $mr): bool
    {
        $this->loadModel('action')->create($this->moduleName, $mr->id, 'merged' . $this->moduleName);

        foreach(array('story', 'bug', 'task') as $type)
        {
            $objects = $this->getLinkList($mr->id, $type);
            foreach($objects as $object)
            {
                $this->action->create($type, $object->id, 'merged' . $this->moduleName, '', helper::createLink($this->moduleName, 'view', "id={$mr->id}"));
            }
        }
        return !dao::isError();
    }

    /**
     * 检查是否有相同的未关闭合并请求。
     * Check same opened mr for source branch.
     *
     * @param  int    $repoID
     * @param  int    $sourceRepoID
     * @param  string $sourceBranch
     * @param  int    $targetRepoID
     * @param  string $targetBranch
     * @access public
     * @return array
     */
    public function checkSameOpened(int $repoID, int $sourceRepoID, string $sourceBranch, int $targetRepoID, string $targetBranch): array
    {
        if($sourceRepoID == $targetRepoID && $sourceBranch == $targetBranch) return array('result' => 'fail', 'message' => $this->lang->ppm->errorLang[1]);
        $dbOpenedID = $this->dao->select('id')->from(TABLE_PPM)
            ->where('repoID')->eq($repoID)
            ->andWhere('sourceRepoID')->eq($sourceRepoID)
            ->andWhere('sourceBranch')->eq($sourceBranch)
            ->andWhere('targetRepoID')->eq($targetRepoID)
            ->andWhere('targetBranch')->eq($targetBranch)
            ->andWhere('status')->eq('opened')
            ->fetch('id');
        if(!empty($dbOpenedID)) return array('result' => 'fail', 'message' => sprintf($this->lang->ppm->hasSameOpenedMR, $dbOpenedID));
        return array('result' => 'success');
    }

    /**
     * 解析API错误信息。
     * Convert API error.
     *
     * @param  array  $message
     * @access public
     * @return string
     */
    public function convertApiError(array|string $message): string
    {
        if(is_array($message)) $message = $message[0];
        if(!is_string($message)) return $message;

        foreach($this->lang->ppm->apiErrorMap as $key => $errorMsg)
        {
            if(strpos($errorMsg, '/') === 0)
            {
                $result = preg_match($errorMsg, $message, $matches);
                if($result) $errorMessage = sprintf(zget($this->lang->ppm->errorLang, $key), zget($matches, 1, $matches[0]));
            }
            elseif($message == $errorMsg)
            {
                $errorMessage = zget($this->lang->ppm->errorLang, $key, $message);
            }
            if(isset($errorMessage)) break;
        }
        return isset($errorMessage) ? $errorMessage : $message;
    }

    /**
     * 判断按钮是否可点击。
     * Adjust the action clickable.
     *
     * @param  object $ppm
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $ppm, string $action): bool
    {
        global $app;

        if($action == 'reopen') return !empty($ppm->status) && $ppm->status == 'closed';
        if($action == 'close')  return !empty($ppm->status) && $ppm->status == 'opened';
        if($action == 'review') return $ppm->status == 'opened' && strpos(",{$ppm->reviewers},", ",{$app->user->account},") !== false;
        if($action == 'submit') return $ppm->reviewStatus == 'pending' || $ppm->reviewStatus == 'rejected' || $ppm->reviewStatus == 'reverting';
        if($action == 'recall') return $ppm->reviewStatus == 'inProgress' && $app->control->loadModel('approval')->canCancel($ppm);
        if($action == 'progress') return !empty($ppm->approvalflow) && !empty($ppm->approval);
        return true;
    }

    /**
     * 执行合并请求流水线。
     * Execute MR pipeline.
     *
     * @param  int    $ppmID
     * @param  int    $jobID
     * @access public
     * @return bool
     */
    public function execJob(int $ppmID, int $jobID): bool
    {
        if(empty($ppmID) || empty($jobID)) return false;

        $ppm = $this->fetchByID($ppmID);
        if(!$ppm) return false;

        $compile = $this->loadModel('job')->exec($jobID, array('sourceBranch' => $ppm->sourceBranch, 'targetBranch' => $ppm->targetBranch), 'commit');
        if(!$compile) return false;

        $newPPM = new stdclass();
        $newPPM->compileID     = $compile->id;
        $newPPM->compileStatus = $compile->status;
        if($newPPM->compileStatus == 'failure')     $newPPM->status = 'closed';
        if($newPPM->compileStatus == 'create_fail') $newPPM->status = 'closed';
        $this->loadModel('repo')->saveRelation($ppmID, 'ppm', $compile->id, 'compile', 'ppmjob');

        $this->dao->update(TABLE_PPM)->data($newPPM)->where('id')->eq($ppmID)->autoCheck()->exec();
        return dao::isError();
    }

    /**
     * 创建合并请求。
     * Insert a merge request.
     *
     * @param  object $ppm
     * @access public
     * @return int|false
     */
    public function insertMr(object $ppm): int|false
    {
        $this->dao->insert(TABLE_PPM)->data($ppm, $this->config->ppm->create->skippedFields)
            ->batchCheck($this->config->ppm->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * 根据分支获取提交记录。
     * Get commit list by branch.
     *
     * @param  object $repo
     * @param  string $sourceBranch
     * @param  string $targetBranch
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getCommitListByBranch(object $repo, string $sourceBranch, string $targetBranch, ?object $pager = null): array
    {
       $params = array();
       $params['gitRef']       = $sourceBranch;
       $params['after']        = $targetBranch;
       $params['page']         = is_null($pager) ? 1 : $pager->pageID;
       $params['pageSize']     = is_null($pager) ? 20 : $pager->recPerPage;
       $params['includeStats'] = true;

       $commits = $this->loadModel('gitfox')->apiGetCommits((int)$repo->id, $params);
       if(!empty($commits->data))
       {
           $pager->recTotal   = $commits->pager->total;
           $pager->recPerPage = $commits->pager->pageSize;
           $pager->pageID     = $commits->pager->page;
       }
       $commits = empty($commits->data) ? array() : zget($commits->data, 'commits', array());
       foreach($commits as $commit)
       {
           $commit->id            = substr($commit->sha, 0, 7);
           $commit->repoID        = $repo->id;
           $commit->committedDate = empty($commit->author) ? '' : $commit->author->when;
           $commit->authorName    = empty($commit->author) ? '' : $commit->author->identity->name;
       }

       return $commits;
    }

    /**
     * 根据分支获取关联的需求、Bug、任务对象.
     * Get linked objects by commits.
     *
     * @param  object $repo
     * @param  array  $commits
     * @param  string $type
     * @param  ?object $pager
     * @access public
     * @return void
     */
    public function getRelationByBranch(object $repo, string $sourceBranch, string $targetBranch, string $type = '', ?object $pager = null)
    {
        if($type == 'all') $type = '';
        $params = array();
        $params['gitRef']   = $sourceBranch;
        $params['after']    = $targetBranch;
        $params['pageSize'] = 100;
        $commitList = array();
        for($i = 0; true; $i++)
        {
            $params['page'] = $i + 1;
            $commits = $this->loadModel('gitfox')->apiGetCommits((int)$repo->id, $params);
            if(empty($commits) || empty($commits->data) || empty($commits->data->commits)) break;
            $commitList = array_merge($commitList, $commits->data->commits);
            if(!empty($commits->pager) && $commits->pager->pageSize < 100) break;
        }

        $relationList = $this->dao->select('t1.BID as id, t1.BType as type')->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_REPOHISTORY)->alias('t2')->on('t1.AID = t2.id')
            ->where('t2.revision')->in(array_column($commitList, 'sha'))
            ->andWhere('t2.repo')->eq(zget($repo, 'id', 0))
            ->andWhere('t1.AType')->eq('revision')
            ->beginIF($type)->andWhere('t1.BType')->eq($type)->fi()
            ->fetchGroup('type', 'id');

        $objectList = array();
        $stories    = empty($relationList['story']) ? array() : $this->loadModel('story')->getByList(array_keys($relationList['story']), '', 'id_desc');
        foreach($stories as $story)
        {
            $object = new stdClass();
            $object->type        = 'story';
            $object->id          = $story->id;
            $object->title       = $story->title;
            $object->status      = $this->lang->story->statusList[$story->status];
            $object->createdBy   = $story->openedBy;
            $object->createdDate = $story->openedDate;
            $object->assignedTo  = $story->assignedTo;
            $object->hasViewPriv = common::hasPriv('story', 'view');

            $objectList[] = $object;
        }

        $tasks = empty($relationList['task']) ? array() : $this->loadModel('task')->getByIdList(array_keys($relationList['task']), 'id_desc');
        foreach($tasks as $task)
        {
            $object = new stdClass();
            $object->type        = 'task';
            $object->id          = $task->id;
            $object->title       = $task->name;
            $object->status      = $this->lang->task->statusList[$task->status];
            $object->createdBy   = $task->openedBy;
            $object->createdDate = $task->openedDate;
            $object->assignedTo  = $task->assignedTo;
            $object->hasViewPriv = common::hasPriv('task', 'view');

            $objectList[] = $object;
        }

        $bugs = empty($relationList['bug']) ? array() : $this->loadModel('bug')->getByIdList(array_keys($relationList['bug']), '`id`, `title`, `status`, `openedBy`, `openedDate`, `assignedTo`', 'id_desc');
        foreach($bugs as $bug)
        {
            $object = new stdClass();
            $object->type        = 'bug';
            $object->id          = $bug->id;
            $object->title       = $bug->title;
            $object->status      = $this->lang->bug->statusList[$bug->status];
            $object->createdBy   = $bug->openedBy;
            $object->createdDate = $bug->openedDate;
            $object->assignedTo  = $bug->assignedTo;
            $object->hasViewPriv = common::hasPriv('bug', 'view');

            $objectList[] = $object;
        }

        if($pager)
        {
            $pager->recTotal = count($objectList);
            $objectList      = array_slice($objectList, ($pager->pageID - 1) * $pager->recPerPage, $pager->recPerPage);
        }

        return $objectList;
    }

    /**
     * 获取MR的审阅者。
     * Get MR reviewers.
     *
     * @param  int $ppmID
     * @access public
     * @return array
     */
    public function getReviewers(int $ppmID): array
    {
        return $this->dao->select('*')->from(TABLE_PPMREVIEWERS)->where('requestID')->eq($ppmID)->fetchAll('account', false);
    }

    /**
     * 添加MR审阅者。
     * Add MR reviewers.
     *
     * @param  object $ppm
     * @param  array  $reviewers
     * @access public
     * @return bool
     */
    public function addReviewers(object $ppm, array $reviewers): bool
    {
        if(empty($ppm->id)) return false;

        $this->loadModel('action');
        $users = $this->loadModel('user')->getPairs('noletter');
        foreach($reviewers as $reviewer)
        {
            $reviewData = new stdClass();
            $reviewData->requestID      = $ppm->id;
            $reviewData->repoID         = $ppm->repoID;
            $reviewData->account        = $reviewer;
            $reviewData->decision       = 'pending';
            $reviewData->sha            = $ppm->sourceSHA;
            $reviewData->createdBy      = $this->app->user->account;
            $reviewData->createdDate    = helper::now();
            $this->dao->insert(TABLE_PPMREVIEWERS)->data($reviewData)->exec();
            if(dao::isError()) return false;

            $this->action->create('ppm', $ppm->id, 'addReviewer', '', zget($users, $reviewer));
            if(dao::isError()) return false;
        }

        return true;
    }

    /**
     * 删除MR审阅者。
     * Delete MR reviewers.
     *
     * @param  int    $ppmID
     * @param  string $reviewer
     * @access public
     * @return bool
     */
    public function deleteReviewer(int $ppmID, string $reviewer): bool
    {
        if(empty($ppmID) || empty($reviewer)) return false;

        $this->dao->delete()->from(TABLE_PPMREVIEWERS)->where('requestID')->eq($ppmID)->andWhere('account')->eq($reviewer)->exec();
        if(dao::isError()) return false;

        $user = $this->dao->select('realname')->from(TABLE_USER)->where('account')->eq($reviewer)->fetch();
        $this->loadModel('action')->create('ppm', $ppmID, 'deleteReviewer', '', $user->realname);
        return true;
    }

    /**
     * 审阅结果。
     * Get MR review result.
     *
     * @param  array|object $reviewers
     * @param  array|object $flow
     * @access public
     * @return string
     */
    public function getReviewResult(array|object $reviewers, array|object $flow): string
    {
        if(empty($reviewers)) return 'rejected';
        $minReviewers = 0;
        if(!empty($flow))
        {
            if(!is_object($flow->definition)) $flow->definition = json_decode($flow->definition);
            $minReviewers = $flow->definition->reviewFlow->approvals->minReviewers;
        }
        if($minReviewers > count($reviewers)) return 'rejected';

        $result = 'approved';
        foreach($reviewers as $reviewer)
        {
            if($reviewer->decision == 'pending') $result = 'inProgress';
            if($reviewer->decision == 'rejected') return 'rejected';
        }

        return $result;
    }

    /**
     * 获取MR的审阅结果列表。
     * Get MR review results.
     *
     * @param  array $mrList
     * @param  int   $repoID
     * @access public
     * @return array
     */
    public function getReviewResults(array $ppmList, int $repoID): array
    {
        $reviewers = $this->dao->select('distinct t1.*, t2.reviewFlowID')->from(TABLE_PPMREVIEWERS)->alias('t1')
            ->leftJoin(TABLE_PPM)->alias('t2')
            ->on('t1.requestID', 't2.id')
            ->where('t1.requestID')->in($ppmList)
            ->fetchAll();
        $repoFlows = $this->loadModel('reporeviewflow')->getList($repoID);

        $reviewerList = array();
        foreach($reviewers as $reviewer)
        {
            $flow = empty($reviewer->reviewFlowID) ? array() : zget($repoFlows, $reviewer->reviewFlowID, array());
            $reviewerList[$reviewer->requestID]['flow'] = $flow;
            $reviewerList[$reviewer->requestID]['reviewers'][$reviewer->account] = $reviewer;
        }

        $reviewResults = array();
        foreach($reviewerList as $ppmID => $reviewerInfo)
        {
            $reviewResults[$ppmID]['result']    = $this->getReviewResult(zget($reviewerInfo, 'reviewers', array()), zget($reviewerInfo, 'flow', array()));
            $reviewResults[$ppmID]['reviewers'] = zget($reviewerInfo, 'reviewers', array());
        }
        return $reviewResults;
    }

    /**
     * 合并MR。
     * Merge MR.
     *
     * @param  int    $ppmID
     * @param  string $mergeType
     * @param  bool   $dryRun
     * @param  bool   $byPass
     * @access public
     * @return object|bool
     */
    public function merge(int $ppmID, string $mergeType, bool $dryRun = false, bool $byPass = false): object|bool
    {
        if($mergeType == 'fast') $mergeType = 'fast-forward';
        $ppm = $this->fetchByID($ppmID);

        $param = array();
        $param['dryRun']      = $dryRun;
        $param['method']      = $mergeType;
        $param['sourceSHA']   = $ppm->sourceSHA;
        $param['bypassRules'] = $byPass;
        if(!in_array($mergeType, array('rebase', 'fast-forward')))
        {
            $repo = $this->loadModel('repo')->getByID($ppm->repoID);
            $param['message'] = "Merge branch {$ppm->sourceBranch} of {$repo->name} (#{$ppm->id})";
        }

        $apiRoot  = $this->loadModel('gitfox')->getApiRoot();
        $url      = sprintf($apiRoot->url, "/repos/{$ppm->targetRepoID}/pullreq/{$ppmID}/merge");
        $response = json_decode(commonModel::http($url, $param, array(), $apiRoot->header, 'json', 'POST'));
        return $this->gitfox->getResponse($response);
    }

    /**
     * 检查合并规则.
     * Check merge rule.
     *
     * @param  int    $repoID
     * @param  string $sourceBranch
     * @param  string $targetBranch
     * @access public
     * @return array
     */
    public function checkMergeRule(int $repoID, string $sourceBranch, string $targetBranch): array
    {
        $branchRuleList           = $this->loadModel('repobranchrule')->getList($repoID);
        $canMergeSourceBranchType = array();
        $canMergeTargetBranchType = array();
        $branchTypeRules          = array();
        foreach($branchRuleList as $branchRule)
        {
            if($branchRule->branchName == $sourceBranch && !empty($branchRule->targetBranch)) $canMergeTargetBranchType = explode(',', $branchRule->targetBranch);
            if($branchRule->branchName == $targetBranch && !empty($branchRule->sourceBranch)) $canMergeSourceBranchType = explode(',', $branchRule->sourceBranch);
            if(empty($branchRule->branchType)) continue;
            $branchTypeRules[$branchRule->branchType] = $branchRule;
        }

        $branchTypeList   = $this->loadModel('repobranchtype')->getByBranches($repoID, array($sourceBranch, $targetBranch));
        $sourceBranchType = empty($branchTypeList) || empty($branchTypeList[$sourceBranch]) ? 0 : $branchTypeList[$sourceBranch]->id;
        $targetBranchType = empty($branchTypeList) || empty($branchTypeList[$targetBranch]) ? 0 : $branchTypeList[$targetBranch]->id;

        $checkSourceBranch = $checkTargetBranch = true;
        $checkResult = array();
        $checkResult[$sourceBranch]['result'] = $checkSourceBranch;
        $checkResult[$targetBranch]['result'] = $checkTargetBranch;
        $sourceTypeTargetRule = empty($branchTypeRules[$sourceBranchType]) || empty($branchTypeRules[$sourceBranchType]->targetBranch) ? array() : explode(',', $branchTypeRules[$sourceBranchType]->targetBranch);
        $targetTypeSourceRule = empty($branchTypeRules[$targetBranchType]) || empty($branchTypeRules[$targetBranchType]->sourceBranch) ? array() : explode(',', $branchTypeRules[$targetBranchType]->sourceBranch);

        if(empty($canMergeTargetBranchType) && !empty($sourceTypeTargetRule) && !in_array($targetBranchType, $sourceTypeTargetRule))
        {
            $checkResult[$sourceBranch]['result'] = false;
            $checkResult[$sourceBranch]['rule']   = $sourceTypeTargetRule;
        }
        if(empty($canMergeSourceBranchType) && !empty($targetTypeSourceRule) && !in_array($sourceBranchType, $targetTypeSourceRule))
        {
            $checkResult[$targetBranch]['result'] = false;
            $checkResult[$targetBranch]['rule']   = $targetTypeSourceRule;
        }
        if(!empty($canMergeTargetBranchType) && !in_array($targetBranchType, $canMergeTargetBranchType))
        {
            $checkResult[$sourceBranch]['result'] = false;
            $checkResult[$sourceBranch]['rule']   = $canMergeTargetBranchType;
        }
        if(!empty($canMergeSourceBranchType) && !in_array($sourceBranchType, $canMergeSourceBranchType))
        {
            $checkResult[$targetBranch]['result'] = false;
            $checkResult[$targetBranch]['rule']   = $canMergeSourceBranchType;
        }
        if(!$checkResult[$targetBranch]['result'] || !$checkResult[$sourceBranch]['result'])
        {
            $branchTypes = $this->repobranchtype->getBranchTypeByRepoID($repoID);
            foreach($checkResult as $branch => $result)
            {
                if(empty($result['rule'])) continue;
                foreach($result['rule'] as $branchType)
                {
                    if(!empty($branchTypes[$branchType])) $checkResult[$branch]['branchType'][$branchType] = $branchTypes[$branchType]->name;
                }
            }
        }

        return $checkResult;
    }

    /**
     * 触发合并请求事件
     * Trigger merge request event
     *
     * @param  int    $repoID
     * @param  int    $ppmID
     * @param  string $type
     * @access public
     * @return object|bool
     */
    public function apiTriggerEvent(int $repoID, int $ppmID, string $type): object|bool
    {
        $apiRoot = $this->loadModel('gitfox')->getApiRoot();

        $data = array();
        $data['type'] = $type;

        $url      = sprintf($apiRoot->url, "/repos/{$repoID}/pullreq/{$ppmID}/event-trigger");
        $response = json_decode(commonModel::http($url, $data, array(), $apiRoot->header, 'json', 'POST'));
        return $this->gitfox->getResponse($response);
    }

    /**
     * 获取合并请求的流水线。
     * Get merge request pipelines.
     *
     * @param  object $ppm
     * @access public
     * @return array
     */
    public function getPipelinesByPPM(object $ppm): array
    {
        if(empty($ppm->sourceSHA) || empty($ppm->sourceBranch)) return array();

        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $url     = sprintf($apiRoot->url, "/pipeline/executions/list-by-pr");

        $params = array();
        $params['ref']    = "refs/pullreq/{$ppm->id}/head";
        $params['commit'] = $ppm->sourceSHA;

        $response = json_decode(commonModel::http($url, $params, array(), $apiRoot->header, 'json', 'GET'));
        $response = $this->gitfox->getResponse($response);
        if(empty($response) || dao::isError()) return array();

        if($ppm->status == 'merged')
        {
            $params['ref']    = 'refs/heads/' . $ppm->targetBranch;
            $params['commit'] = $ppm->mergeSHA;

            $mergeResponse = json_decode(commonModel::http($url, $params, array(), $apiRoot->header, 'json', 'GET'));
            $mergeResponse = $this->gitfox->getResponse($mergeResponse);
            if(dao::isError()) return $response;
            $response = array_merge($response, $mergeResponse);
        }
        $scanTasks = $this->gitfox->request('/scan/tasks/list', 'POST', array('repoID' => $ppm->repoID));
        $scanTasks = empty($scanTasks->data) ? array() : $scanTasks->data;

        $tasks = array();
        foreach($scanTasks as $scanTask)
        {
            $tasks[$scanTask->executionID] = $scanTask;
        }

        foreach($response as $key => $value)
        {
            if(isset($tasks[$value->id]))
            {
                $response[$key]->task = $tasks[$value->id];
            }
        }

        return $response;
    }

    /**
     * 获取提交对应的缺陷。
     * Get commits bugs.
     *
     * @param  int     $repoID
     * @param  int     $ppmID
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getBugsByCommits(int $repoID, int $ppmID, ?object $pager = null): array
    {
        $commits = $this->apiGetMRCommits($repoID, $ppmID);
        if(empty($commits)) return array();

        $commitList = array_column($commits, 'sha');

        return $this->dao->select('*, concat("code") as source')->from(TABLE_BUG)
            ->where('repo')->eq($repoID)
            ->andWhere('v2')->in($commitList)
            ->andWhere('deleted')->eq(0)
            ->page($pager)
            ->fetchAll('id');
    }
}

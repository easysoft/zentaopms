<?php
declare(strict_types=1);
/**
 * The model file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
class mrModel extends model
{

    /**
     * 获取合并请求列表.
     * Get MR list of gitlab project.
     *
     * @param  string     $mode
     * @param  string     $param
     * @param  string     $orderBy
     * @param  array|bool $filterProjects
     * @param  int        $repoID
     * @param  int        $objectID
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getList(string $mode = 'all', string $param = 'all', string $orderBy = 'id_desc', array $filterProjects = array(), int $repoID = 0, int $objectID = 0, object $pager = null): array
    {
        /* If filterProjects equals false,it means no permission. */
        if($filterProjects === false) return array();

        $filterProjectSql = '';
        if(!$this->app->user->admin and !empty($filterProjects))
        {
            foreach($filterProjects as $hostID => $projects)
            {
                $projectIDList = array_keys($projects);
                if(!empty($projectIDList)) $filterProjectSql .= "(hostID = {$hostID} and sourceProject " . helper::dbIN($projectIDList) . ") or ";
            }

            if($filterProjectSql) $filterProjectSql = '(' . substr($filterProjectSql, 0, -3) . ')'; // Remove last or.
        }

        return $this->dao->select('*')
            ->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->beginIF($mode == 'status' and $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($mode == 'assignee' and $param != 'all')->andWhere('assignee')->eq($param)->fi()
            ->beginIF($mode == 'creator' and $param != 'all')->andWhere('createdBy')->eq($param)->fi()
            ->beginIF($filterProjectSql)->andWhere($filterProjectSql)->fi()
            ->beginIF($repoID)->andWhere('repoID')->eq($repoID)->fi()
            ->beginIF($objectID)->andWhere('executionID')->eq($objectID)->fi()
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
            ->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('repoID')->eq($repoID)
            ->orderBy('id')
            ->fetchPairs('id', 'title');
    }

    /**
     * 获取所有服务器的项目。如果不是管理员，则项目成员的角色应高于来宾。
     * Get all gitlab server projects. If not an administrator, the role of project member should be higher than guest.
     *
     * @param  int    $repoID
     * @param  string $scm
     * @access public
     * @return array
     */
    public function getAllProjects(int $repoID = 0, string $scm = 'Gitlab'): array
    {
        $hostID = $this->dao->select('hostID')->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('repoID')->eq($repoID)
            ->fetch('hostID');

        return $this->{'get' . $scm . 'Projects'}($hostID);
    }

    /**
     * 获取Gitea服务器的项目.
     * Get gitea projects.
     *
     * @param  int    $hostID
     * @access public
     * @return array
     */
    public function getGiteaProjects(int $hostID = 0): array
    {
        $projects = $this->loadModel('gitea')->apiGetProjects($hostID);
        return array($hostID => helper::arrayColumn($projects, null, 'full_name'));
    }

    /**
     * 获取Gogs服务器的项目.
     * Get gogs projects.
     *
     * @param  int    $hostID
     * @access public
     * @return array
     */
    public function getGogsProjects(int $hostID = 0): array
    {
        $projects = $this->loadModel('gogs')->apiGetProjects($hostID);
        return array($hostID => helper::arrayColumn($projects, null, 'full_name'));
    }

    /**
     * 获取Gitlab服务器的项目.
     * Get gitlab projects.
     *
     * @param  int    $hostID
     * @param  array  $projectIds
     * @access public
     * @return array
     */
    public function getGitlabProjects(int $hostID = 0, array $projectIds = array()): array
    {
        $gitlabUsers = $this->loadModel('gitlab')->getListByAccount();
        if(!$this->app->user->admin && !isset($gitlabUsers[$hostID])) return array();

        $allProjects = $allGroups  = array();
        $minProject  = $maxProject = 0;
        /* Mysql string to int. */
        $projectCount = $this->dao->select('min(sourceProject + 0) as minSource, MAX(sourceProject + 0) as maxSource,MIN(targetProject) as minTarget,MAX(targetProject) as maxTarget')->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('hostID')->eq($hostID)
            ->fetch();
        if($projectCount)
        {
            $minProject = min($projectCount->minSource, $projectCount->minTarget);
            $maxProject = max($projectCount->maxSource, $projectCount->maxTarget);
        }

        $allProjects[$hostID] = $this->gitlab->apiGetProjects($hostID, 'false', $minProject, $maxProject);
        if($projectIds)
        {
            foreach($allProjects[$hostID] as $index => $project)
            {
                if(!in_array($project->id, $projectIds)) unset($allProjects[$hostID][$index]);
            }
        }

        /* If not an administrator, need to obtain group member information. */
        $groupIDList = array(0 => 0);
        if(!$this->app->user->admin)
        {
            $groups = $this->gitlab->apiGetGroups($hostID, 'name_asc', 'reporter');
            foreach($groups as $group) $groupIDList[] = $group->id;
        }
        $allGroups[$hostID] = $groupIDList;

        $allProjectPairs = array();
        foreach($allProjects as $hostID => $projects)
        {
            foreach($projects as $project)
            {
                if($this->gitlab->checkUserAccess($hostID, 0, $project, $allGroups[$hostID], 'reporter') == false) continue;
                $project->isDeveloper = $this->gitlab->checkUserAccess($hostID, 0, $project, $allGroups[$hostID], 'developer');

                $allProjectPairs[$hostID][$project->id] = $project;
            }
        }

        return $allProjectPairs;
    }

    /**
     * 创建本地合并请求。
     * Create a local merge request.
     *
     * @param  object $MR
     * @access public
     * @return bool
     */
    public function createMR(object $MR): bool
    {
        /* Exec Job */
        if(isset($MR->jobID) && $MR->jobID)
        {
            $pipeline = $this->loadModel('job')->exec($MR->jobID);
            if(!empty($pipeline->queue))
            {
                $compile = $this->loadModel('compile')->getByQueue($pipeline->queue);
                $MR->compileID     = $compile->id;
                $MR->compileStatus = $compile->status;
            }
        }

        return $this->mrTao->insertMr($MR);
    }

    /**
     * 创建合并请求。
     * Create MR function.
     *
     * @param  object $MR
     * @access public
     * @return array
     */
    public function create(object $MR): array
    {
        $result = $this->checkSameOpened($MR->hostID, (string)$MR->sourceProject, $MR->sourceBranch, (string)$MR->targetProject, $MR->targetBranch);
        if($result['result'] == 'fail') return $result;

        $this->createMR($MR);
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $MRID = $this->dao->lastInsertId();
        $this->loadModel('action')->create('mr', $MRID, 'opened');

        $rawMR = $this->apiCreateMR($MR->hostID, (string)$MR->sourceProject, $MR);

        /**
         * Another open merge request already exists for this source branch.
         * The type of variable `$rawMR->message` is array.
         */
        if(isset($rawMR->message) and !isset($rawMR->iid))
        {
            $this->dao->delete()->from(TABLE_MR)->where('id')->eq($MRID)->exec();

            $errorMessage = $this->convertApiError($rawMR->message);
            return array('result' => 'fail', 'message' => sprintf($this->lang->mr->apiError->createMR, $errorMessage));
        }

        /* Create MR failed. */
        if(!isset($rawMR->iid))
        {
            $this->dao->delete()->from(TABLE_MR)->where('id')->eq($MRID)->exec();
            return array('result' => 'fail', 'message' => $this->lang->mr->createFailedFromAPI);
        }

        /* Create a todo item for this MR. */
        if(empty($MR->jobID)) $this->apiCreateMRTodo($this->post->hostID, $this->post->targetProject, $rawMR->iid);

        $newMR = new stdclass;
        $newMR->mriid       = $rawMR->iid;
        $newMR->status      = $rawMR->state;
        $newMR->mergeStatus = $rawMR->merge_status;

        /* Update MR in Zentao database. */
        $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($MRID)->autoCheck()->exec();

        /* Link stories,bugs and tasks. */
        $MR->id    = $MRID;
        $MR->mriid = $newMR->mriid;
        $this->linkObjects($MR);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => helper::createLink('mr', 'browse', $this->app->tab == 'execution' ? "repoID=0&mode=status&param=opened&objectID={$this->post->executionID}" : ''));
    }

    /**
     * 创建合并请求后的操作。
     * Create MR after operation.
     *
     * @param  int    $MRID
     * @param  object $MR
     * @access public
     * @return int|false
     */
    public function afterApiCreate(int $MRID, object $MR): int|false
    {
        if($MR->hasNoConflict == '0' && $MR->mergeStatus == 'can_be_merged' && $MR->jobID)
        {
            $extraParam = array('sourceBranch' => $MR->sourceBranch, 'targetBranch' => $MR->targetBranch);
            $pipeline   = $this->loadModel('job')->exec($MR->jobID, $extraParam);
            $newMR      = new stdClass();
            if(!empty($pipeline->queue))
            {
                $compile = $this->loadModel('compile')->getByQueue($pipeline->queue);
                $newMR->compileID     = $compile->id;
                $newMR->compileStatus = $compile->status;
                if($newMR->compileStatus == 'failure') $newMR->status = 'closed';
            }
            else
            {
                $newMR->compileStatus = $pipeline->status;
                if($newMR->compileStatus == 'create_fail') $newMR->status = 'closed';
            }

            /* Update MR in Zentao database. */
            $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($MRID)->autoCheck()->exec();
            if(dao::isError()) return false;
        }
        return $MRID;
    }

    /**
     * 通过API创建合并请求。
     * Create MR function by api.
     *
     * @access public
     * @return bool|int
     */
    public function apiCreate(): int|false
    {
        $postData = fixer::input('post')->get();

        $repo = $this->dao->findByID($postData->repoID)->from(TABLE_REPO)->fetch();
        if(empty($repo))
        {
            dao::$errors[] = 'No matched gitlab.';
            return false;
        }

        /* Process and insert mr data. */
        $MR = new stdClass();
        $MR->hostID         = $repo->client;
        $MR->sourceProject  = $repo->path;
        $MR->sourceBranch   = $postData->sourceBranch;
        $MR->targetProject  = $repo->path;
        $MR->targetBranch   = $postData->targetBranch;
        $MR->diffs          = $postData->diffs;
        $MR->title          = $this->lang->mr->common . ' ' . $postData->sourceBranch . $this->lang->mr->to . $postData->targetBranch ;
        $MR->repoID         = $repo->id;
        $MR->jobID          = $postData->jobID;
        $MR->status         = 'opened';
        $MR->synced         = '0';
        $MR->needCI         = '1';
        $MR->hasNoConflict  = $postData->mergeStatus ? '0' : '1';
        $MR->mergeStatus    = $postData->mergeStatus ? 'can_be_merged' : 'cannot_be_merged';
        $MR->createdBy      = $this->app->user->account;
        $MR->createdDate    = date('Y-m-d H:i:s');

        if($MR->sourceProject == $MR->targetProject && $MR->sourceBranch == $MR->targetBranch)
        {
            dao::$errors[] = $this->lang->mr->errorLang[1];
            return false;
        }

        $result = $this->checkSameOpened($MR->hostID, (string)$MR->sourceProject, $MR->sourceBranch, (string)$MR->targetProject, $MR->targetBranch);
        if($result['result'] == 'fail')
        {
            dao::$errors[] = $result['message'];
            return false;
        }

        $this->mrTao->insertMr($MR);
        if(dao::isError()) return false;

        $MRID = $this->dao->lastInsertId();
        $this->loadModel('action')->create('mr', $MRID, 'opened');

        /* Exec Job */
        return $this->afterApiCreate($MRID, $MR);
    }

    /**
     * 更新合并请求。
     * Edit MR function.
     *
     * @param  int    $MRID
     * @param  object $MR
     * @access public
     * @return array
     */
    public function update(int $MRID, object $MR): array
    {
        $oldMR = $this->fetchByID($MRID);

        if($oldMR->sourceProject == $oldMR->targetProject and $oldMR->sourceBranch == $MR->targetBranch) dao::$errors['targetBranch'] = $this->lang->mr->errorLang[1];
        $this->dao->update(TABLE_MR)->data($MR)->checkIF($MR->needCI, 'jobID',  'notempty');
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        /* Exec Job */
        if(isset($MR->jobID) && $MR->jobID)
        {
            $pipeline = $this->loadModel('job')->exec($MR->jobID);

            if(!empty($pipeline->queue))
            {
                $compile = $this->loadModel('compile')->getByQueue($pipeline->queue);
                $MR->compileID = $compile->id;
                $MR->compileStatus = $compile->status;
            }
        }

        /* Known issue: `reviewer_ids` takes no effect. */
        $rawMR = $this->apiUpdateMR($oldMR->hostID, $oldMR->targetProject, $oldMR->mriid, $MR);
        if(!isset($rawMR->id) and isset($rawMR->message))
        {
            $errorMessage = $this->convertApiError($rawMR->message);
            return array('result' => 'fail', 'message' => $errorMessage);
        }

        /* Update MR in Zentao database. */
        $this->dao->update(TABLE_MR)->data($MR, $this->config->mr->edit->skippedFields)
            ->where('id')->eq($MRID)
            ->batchCheck($this->config->mr->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        $MR = $this->fetchByID($MRID);
        $this->linkObjects($MR);
        $changes = common::createChanges($oldMR, $MR);
        $actionID = $this->loadModel('action')->create('mr', $MRID, 'edited');
        if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        $this->createMRLinkedAction($MRID, 'editmr', $MR->editedDate);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => helper::createLink('mr', 'browse'));
    }

    /**
     * 更新合并请求关联信息。
     * Update MR linked info.
     *
     * @param  int    $MRID
     * @param  string $action  createmr|editmr|removemr
     * @param  string $actionDate
     * @access public
     * @return bool
     */
    public function createMRLinkedAction(int $MRID, string $action, string $actionDate = ''): bool
    {
        if(empty($actionDate)) $actionDate = helper::now();

        $MRAction = $actionDate . '::' . $this->app->user->account . '::' . helper::createLink('mr', 'view', "mr={$MRID}");

        $linkedStories = $this->mrTao->getLinkedObjectPairs($MRID, 'story');
        $linkedTasks   = $this->mrTao->getLinkedObjectPairs($MRID, 'task');
        $linkedBugs    = $this->mrTao->getLinkedObjectPairs($MRID, 'bug');

        $this->loadModel('action');
        foreach($linkedStories as $storyID) $this->action->create('story', $storyID, $action, '', $MRAction);
        foreach($linkedTasks as $taskID)    $this->action->create('task', $taskID, $action, '', $MRAction);
        foreach($linkedBugs as $bugID)      $this->action->create('bug', $bugID, $action, '', $MRAction);
        return !dao::isError();
    }

    /**
     * 通过API同步合并请求。
     * Sync MR from GitLab API to Zentao database.
     *
     * @param  object  $MR
     * @access public
     * @return object|false
     */
    public function apiSyncMR(object $MR): object|false
    {
        $rawMR = $this->apiGetSingleMR($MR->hostID, $MR->targetProject, $MR->mriid);
        /* Sync MR in ZenTao database whatever status of MR in GitLab. */
        if(isset($rawMR->iid))
        {
            $map = $this->config->mr->maps->sync;
            if($rawMR->gitService == 'gitlab')
            {
                $gitUsers = $this->loadModel('gitlab')->getUserIdAccountPairs($MR->hostID);
            }
            else
            {
                $gitUsers = $this->loadModel($rawMR->gitService)->getUserAccountIdPairs($MR->hostID, 'openID,account');
            }

            $newMR = new stdclass;
            foreach($map as $syncField => $config)
            {
                $value = '';
                list($field, $optionType, $options) = explode('|', $config);

                if($optionType == 'field') $value = $rawMR->$field;
                if($optionType == 'userPairs')
                {
                    $gitUserID = '';
                    if(isset($rawMR->$field))
                    {
                        $values = $rawMR->$field;
                        if(isset($values[0])) $gitUserID = $values[0]->$options;
                    }
                    $value = zget($gitUsers, $gitUserID, '');
                }

                if($value) $newMR->$syncField = $value;
            }

            /* For compatibility with PHP 5.4 . */
            $condition = (array)$newMR;
            if(empty($condition)) return false;

            /* Update compile status of current MR object */
            if(isset($MR->needCI) && $MR->needCI == '1') $newMR->compileStatus = empty($MR->compileID) ? 'failed' : $this->loadModel('compile')->getByID($MR->compileID)->status;

            /* Update MR in Zentao database. */
            $this->dao->update(TABLE_MR)->data($newMR)
                ->where('id')->eq($MR->id)
                ->exec();
        }
        return $this->dao->findByID($MR->id)->from(TABLE_MR)->fetch();
    }

    /**
     * 通过API批量同步合并请求。
     * Batch Sync GitLab MR Database.
     *
     * @param  array  $MRList
     * @access public
     * @return array
     */
    public function batchSyncMR(array $MRList): array
    {
        if(empty($MRList)) return array();

        foreach($MRList as $key => $MR)
        {
            if($MR->status != 'opened') continue;
            $MRList[$key] = $this->apiSyncMR($MR);
        }

        return $MRList;
    }

    /**
     * 创建远程合并请求。
     * Create MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#create-mr
     * @param  int    $hostID
     * @param  string $projectID
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiCreateMR(int $hostID, string $projectID, object $MR): object
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);

        $MRObject = new stdclass;
        $MRObject->title = $MR->title;
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests");

            $MRObject->target_project_id    = $MR->targetProject;
            $MRObject->source_branch        = $MR->sourceBranch;
            $MRObject->target_branch        = $MR->targetBranch;
            $MRObject->description          = $MR->description;
            $MRObject->remove_source_branch = $MR->removeSourceBranch == '1' ? true : false;
            $MRObject->squash               = $MR->squash == '1' ? 1 : 0;
            if($MR->assignee)
            {
                $gitlabAssignee = $this->gitlab->getUserIDByZentaoAccount($this->post->hostID, $MR->assignee);
                if($gitlabAssignee) $MRObject->assignee_ids = $gitlabAssignee;
            }
            return json_decode(commonModel::http($url, $MRObject));
        }
        elseif(in_array($host->type, array('gitea', 'gogs')))
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls");

            $MRObject->head = $MR->sourceBranch;
            $MRObject->base = $MR->targetBranch;
            $MRObject->body = $MR->description;
            if(!$MR->assignee)
            {
                $assignee = $this->{$host->type}->getUserIDByZentaoAccount($this->post->hostID, $MR->assignee);
                if($assignee) $MRObject->assignee = $assignee;
            }

            $mergeResult = json_decode(commonModel::http($url, $MRObject));
            if(isset($mergeResult->number)) $mergeResult->iid = $mergeResult->number;
            if(isset($mergeResult->mergeable))
            {
                if($mergeResult->mergeable) $mergeResult->merge_status = 'can_be_merged';
                if(!$mergeResult->mergeable) $mergeResult->merge_status = 'cannot_be_merged';
            }
            if(isset($mergeResult->state) and $mergeResult->state == 'open') $mergeResult->state = 'opened';
            if(isset($mergeResult->merged) and $mergeResult->merged) $mergeResult->state = 'merged';
            return $mergeResult;
        }
    }

    /**
     * 通过Api获取合并请求列表。
     * Get MR list by API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  string $scm
     * @access public
     * @return array
     */
    public function apiGetMRList(int $hostID, string $projectID, string $scm = 'Gitlab'): array
    {
        if($scm == 'Gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests");
        }
        else
        {
            $url = sprintf($this->loadModel(strtolower($scm))->getApiRoot($hostID), "/repos/$projectID/pulls");
        }

        $response = json_decode(commonModel::http($url));
        if(empty($response) || isset($response->message)) return array();

        if(in_array($scm, array('Gitea', 'Gogs')))
        {
            foreach($response as $MR)
            {
                if(empty($MR)) continue;
                $MR->iid   = $scm == 'Gitea' ? $MR->number : $MR->id;
                $MR->state = $MR->state == 'open' ? 'opened' : $MR->state;
                if($MR->merged) $MR->state = 'merged';

                $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
                $MR->description       = $MR->body;
                $MR->target_branch     = $scm == 'Gitea' ? $MR->base->ref : $MR->base->ref;
                $MR->source_branch     = $scm == 'Gitea' ? $MR->head->ref : $MR->head->ref;
                $MR->source_project_id = $projectID;
                $MR->target_project_id = $projectID;
            }
        }

        return $response;
    }

    /**
     * 通过API查看是否有相同的合并请求。
     * Get same opened mr by api.
     *
     * @param  int    $hostID
     * @param  string $sourceProject
     * @param  string $sourceBranch
     * @param  string $targetProject
     * @param  string $targetBranch
     * @access public
     * @return object|null
     */
    public function apiGetSameOpened(int $hostID, string $sourceProject, string $sourceBranch, string $targetProject, string $targetBranch): object|null
    {
        if(empty($hostID) || empty($sourceProject) || empty($sourceBranch) ||  empty($targetProject) || empty($targetBranch)) return null;

        $url = sprintf($this->loadModel('gitlab')->getApiRoot((int)$hostID), "/projects/{$sourceProject}/merge_requests") . "&state=opened&source_branch={$sourceBranch}&target_branch={$targetBranch}";
        $response = json_decode(commonModel::http($url));

        if($response)
        {
            foreach($response as $MR)
            {
                if(empty($MR->source_project_id) or empty($MR->target_project_id)) return null;
                if($MR->source_project_id == $sourceProject and $MR->target_project_id == $targetProject) return $MR;
            }
        }
        return null;
    }

    /**
     * 通过API获取单个合并请求。
     * Get single MR by API.
     *
     * @param  int    $hostID
     * @param  string $projectID  targetProject
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetSingleMR(int $hostID, string $projectID, int $MRID): object
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID, false), "/projects/$projectID/merge_requests/$MRID");
            $MR  = json_decode(commonModel::http($url));
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            $MR  = json_decode(commonModel::http($url));
            if(isset($MR->url) || isset($MR->html_url))
            {
                $diff = $this->apiGetDiffs($hostID, $projectID, $MRID);

                $MR->web_url = $host->type == 'gitea' ? $MR->url : $MR->html_url;
                $MR->iid     = $host->type == 'gitea' ? $MR->number : $MR->id;
                $MR->state   = $MR->state == 'open' ? 'opened' : $MR->state;
                if($MR->merged) $MR->state = 'merged';

                $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
                $MR->changes_count     = empty($diff) ? 0 : 1;
                $MR->description       = $MR->body;
                $MR->target_branch     = $host->type == 'gitea' ? $MR->base->ref : $MR->base_branch;
                $MR->source_branch     = $host->type == 'gitea' ? $MR->head->ref : $MR->head_branch;
                $MR->source_project_id = $projectID;
                $MR->target_project_id = $projectID;
                $MR->has_conflicts     = empty($diff) ? true : false;
            }
        }

        $MR->gitService = $host->type;
        return $MR;
    }

    /**
     * 通过API获取合并请求的提交信息。
     * Get MR commits by API.
     *
     * @param  int    $hostID
     * @param  string $projectID  targetProject
     * @param  int    $MRID
     * @access public
     * @return array
     */
    public function apiGetMRCommits(int $hostID, string $projectID, int $MRID): array
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/commits");
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID/commits");
        }

        return json_decode(commonModel::http($url));
    }

    /**
     * 通过API更新合并请求。
     * Update MR by API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiUpdateMR(int $hostID, string $projectID, int $MRID, object $MR): object
    {
        $host  = $this->loadModel('pipeline')->getByID($hostID);
        $newMR = array('title' => $MR->title);
        if($host->type == 'gitlab')
        {
            $newMR['description']          = $MR->description;
            $newMR['target_branch']        = $MR->targetBranch;
            $newMR['remove_source_branch'] = $MR->removeSourceBranch == '1' ? true : false;
            $newMR['squash']               = $MR->squash == '1' ? 1 : 0;
            if($MR->assignee)
            {
                $gitlabAssignee = $this->loadModel('gitlab')->getUserIDByZentaoAccount($hostID, $MR->assignee);
                if($gitlabAssignee) $newMR['assignee_ids'] = $gitlabAssignee;
            }

            $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID");
            return json_decode(commonModel::http($url, $newMR, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");

            $newMR['base'] = $MR->targetBranch;
            $newMR['body'] = $MR->description;
            if($MR->assignee)
            {
                $assignee = $this->{$host->type}->getUserIDByZentaoAccount($this->post->hostID, $MR->assignee);
                if($assignee) $newMR['assignee'] = $assignee;
            }
            $mergeResult = json_decode(commonModel::http($url, $newMR, array(), array(), 'json', 'PATCH'));
            if(isset($mergeResult->number)) $mergeResult->iid = $host->type == 'gitea' ? $mergeResult->number : $mergeResult->id;
            if(isset($mergeResult->mergeable))
            {
                if($mergeResult->mergeable)  $mergeResult->merge_status = 'can_be_merged';
                if(!$mergeResult->mergeable) $mergeResult->merge_status = 'cannot_be_merged';
            }
            if(isset($mergeResult->state) && $mergeResult->state == 'open') $mergeResult->state = 'opened';
            if(isset($mergeResult->merged) && $mergeResult->merged)         $mergeResult->state = 'merged';
            return $mergeResult;
        }
    }

    /**
     * 通过API删除合并请求。
     * Delete MR by API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @access public
     * @return object|null
     */
    public function apiDeleteMR(int $hostID, string $projectID, int $MRID): object|null
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID");
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
        }
        else
        {
            $rowMR = $this->apiGetSingleMR($hostID, $projectID, $MRID);
            if($rowMR->state == 'opened')
            {
                $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
                return json_decode(commonModel::http($url, array('state' => 'closed'), array(), array(), 'json', 'PATCH'));
            }
        }

        return null;
    }

    /**
     * 通过API关闭合并请求。
     * Close MR by API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiCloseMR(int $hostID, string $projectID, int $MRID): object
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID") . '&state_event=close';
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            return json_decode(commonModel::http($url, array('state' => 'closed'), array(), array(), 'json', 'PATCH'));
        }
    }

    /**
     * 通过API重新打开合并请求。
     * Reopen MR by API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiReopenMR(int $hostID, string $projectID, int $MRID): object
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID") . '&state_event=reopen';
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            $MR  = json_decode(commonModel::http($url, array('state' => 'open'), array(), array(), 'json', 'PATCH'));
            $MR->iid   = $host->type == 'gitea' ? $MR->number : $MR->id;
            $MR->state = $MR->state == 'open' ? 'opened' : $MR->state;
            if($MR->merged) $MR->state = 'merged';

            $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
            $MR->description       = $MR->body;
            $MR->target_branch     = $host->type == 'gitea' ? $MR->base->ref : $MR->base_branch;
            $MR->source_branch     = $host->type == 'gitea' ? $MR->head->ref : $MR->head_branch;
            $MR->source_project_id = $projectID;
            $MR->target_project_id = $projectID;

            return $MR;
        }
    }

    /**
     * 通过API接受合并请求。
     * Accept MR by API.
     *
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiAcceptMR(object $MR): object
    {
        $host = $this->loadModel('pipeline')->getByID($MR->hostID);
        if($host->type == 'gitlab')
        {
            $apiRoot    = $this->loadModel('gitlab')->getApiRoot($MR->hostID);
            $approveUrl = sprintf($apiRoot, "/projects/$MR->targetProject/merge_requests/$MR->mriid/approved");
            commonModel::http($approveUrl, null, array(CURLOPT_CUSTOMREQUEST => 'POST'));

            $url = sprintf($apiRoot, "/projects/$MR->targetProject/merge_requests/$MR->mriid/merge");
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $apiRoot = $this->loadModel($host->type)->getApiRoot($MR->hostID);
            $url     = sprintf($apiRoot, "/repos/$MR->targetProject/pulls/$MR->mriid/merge");

            $merge = ($MR and $MR->squash == '1') ? 'squash' : 'merge';
            $data  = array('Do' => $merge);
            if($MR->removeSourceBranch == '1') $data['delete_branch_after_merge'] = true;

            $rowMR = json_decode(commonModel::http($url, $data, array(), array(), 'json', 'POST'));
            if(!isset($rowMR->massage))
            {
                $rowMR = $this->apiGetSingleMR($MR->hostID, $MR->targetProject, $MR->mriid);
                if($data['delete_branch_after_merge'] == true) $this->loadModel('gogs')->apiDeleteBranch($MR->hostID, $MR->targetProject, $MR->sourceBranch);
            }

            return $rowMR;
        }
    }

    /**
     * 获取合并请求的对比信息。
     * Get MR diff versions by API.
     *
     * @param  object $MR
     * @param  string $encoding
     * @access public
     * @return array
     */
    public function getDiffs(object $MR, string $encoding = ''): array
    {
        $repo = $this->loadModel('repo')->getByID($MR->repoID);
        if(!$repo) return array();

        $host = $this->loadModel('pipeline')->getByID($MR->hostID);
        $repo->gitService = $host->id;
        $repo->project    = $MR->targetProject;
        $repo->password   = $host->token;
        $repo->account    = '';
        $repo->encoding   = $encoding;

        $lines = array();
        if($host->type == 'gitlab')
        {
            $diffVersions = array();
            if($MR->synced) $diffVersions = $this->apiGetDiffVersions($MR->hostID, $MR->targetProject, $MR->mriid);

            foreach($diffVersions as $diffVersion)
            {
                $singleDiff = $this->apiGetSingleDiffVersion($MR->hostID, $MR->targetProject, $MR->mriid, $diffVersion->id);
                if($singleDiff->state == 'empty') continue;

                $diffs = $singleDiff->diffs;
                foreach($diffs as $diff)
                {
                    $lines[] = sprintf("diff --git a/%s b/%s", $diff->old_path, $diff->new_path);
                    $lines[] = sprintf("index %s ... %s %s ", $singleDiff->head_commit_sha, $singleDiff->base_commit_sha, $diff->b_mode);
                    $lines[] = sprintf("--a/%s", $diff->old_path);
                    $lines[] = sprintf("--b/%s", $diff->new_path);
                    $diffLines = explode("\n", $diff->diff);
                    foreach($diffLines as $diffLine) $lines[] = $diffLine;
                }
            }
        }
        else
        {
            $diffs = $this->apiGetDiffs($MR->hostID, $MR->targetProject, $MR->mriid);
            $lines = explode("\n", $diffs);
        }

        if(empty($MR->synced))
        {
            $diffs = preg_replace('/^\s*$\n?\r?/m', '', $MR->diffs);
            $lines = explode("\n", $diffs);
        }

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        return $scm->engine->parseDiff($lines);
    }

    /**
     * 通过API创建合并请求待办。
     * Create a todo item for merge request.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiCreateMRTodo(int $hostID, string $projectID, int $MRID): object
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/todo");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'POST')));
    }

    /**
     * 通过API获取合并请求的对比版本信息。
     * Get diff versions of MR from GitLab API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @access public
     * @return array
     */
    public function apiGetDiffVersions(int $hostID, string $projectID, int $MRID): array
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/versions");
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过API获取合并请求的单个对比版本信息。
     * Get a single diff version of MR from GitLab API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @param  int    $versionID
     * @access public
     * @return object
     */
    public function apiGetSingleDiffVersion(int $hostID, string $projectID, int $MRID, int $versionID): object
    {
        $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/versions/$versionID");
        return json_decode(commonModel::http($url));
    }

    /**
     * 通过Gitea API获取合并请求的对比信息。
     * Get diff of MR from Gitea API.
     *
     * @param  int    $hostID
     * @param  string $projectID
     * @param  int    $MRID
     * @access public
     * @return string
     */
    public function apiGetDiffs(int $hostID, string $projectID, int $MRID): string
    {
        $url = sprintf($this->loadModel('gitea')->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID.diff");
        return commonModel::http($url);
    }

    /**
     * 审核合并请求。
     * Reject or Approve this MR.
     *
     * @param  object $MR
     * @param  string $action
     * @param  string $comment
     * @return array
     */
    public function approve(object $MR, string $action = 'approve', string $comment = ''): array
    {
        $actionID = $this->loadModel('action')->create('mr', $MR->id, $action);

        $oldMR = $MR;
        if(isset($MR->status) and $MR->status == 'opened')
        {
            $rawApprovalStatus = zget($MR, 'approvalStatus', '');
            if($action == 'reject'  && $rawApprovalStatus != 'rejected') $MR->approvalStatus = 'rejected';
            if($action == 'approve' && $rawApprovalStatus != 'approved') $MR->approvalStatus = 'approved';
            if(isset($MR->approvalStatus) && $rawApprovalStatus != $MR->approvalStatus)
            {
                $changes = common::createChanges($oldMR, $MR);
                $this->action->logHistory($actionID, $changes);

                $MR->approver = $this->app->user->account;
                $this->dao->update(TABLE_MR)->data($MR)
                    ->where('id')->eq($MR->id)
                    ->exec();
                if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

                /* Save approval history into db. */
                $approval = new stdClass;
                $approval->date    = helper::now();
                $approval->mrID    = $MR->id;
                $approval->account = $MR->approver;
                $approval->action  = $action;
                $approval->comment = $comment;
                $this->dao->insert(TABLE_MRAPPROVAL)->data($approval, $this->config->mrapproval->create->skippedFields)
                    ->batchCheck($this->config->mrapproval->create->requiredFields, 'notempty')
                    ->autoCheck()
                    ->exec();
                if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

                return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true);
            }
        }
        return array('result' => 'fail', 'message' => $this->lang->mr->repeatedOperation, 'load' => helper::createLink('mr', 'view', "mr={$MR->id}"));
    }

    /**
     * 关闭合并请求。
     * Close this MR.
     *
     * @param  object $MR
     * @access public
     * @return array
     */
    public function close(object $MR): array
    {
        $actionID = $this->loadModel('action')->create('mr', $MR->id, 'closed');
        $rawMR    = $this->apiCloseMR($MR->hostID, $MR->targetProject, $MR->mriid);
        $changes  = common::createChanges($MR, $rawMR);
        $this->action->logHistory($actionID, $changes);

        if(isset($rawMR->state) && $rawMR->state == 'closed') return array('result' => 'success', 'message' => $this->lang->mr->closeSuccess, 'load' => helper::createLink('mr', 'view', "mr={$MR->id}"));
        return array('result' => 'fail', 'message' => $this->lang->fail, 'load' => helper::createLink('mr', 'view', "mr={$MR->id}"));
    }

    /**
     * 重新打开合并请求。
     * Reopen this MR.
     *
     * @param  object $MR
     * @access public
     * @return array
     */
    public function reopen(object $MR): array
    {
        $actionID = $this->loadModel('action')->create('mr', $MR->id, 'reopen');
        $rawMR    = $this->apiReopenMR($MR->hostID, $MR->targetProject, $MR->mriid);
        $changes  = common::createChanges($MR, $rawMR);
        $this->action->logHistory($actionID, $changes);

        if(isset($rawMR->state) && $rawMR->state == 'opened') return array('result' => 'success', 'message' => $this->lang->mr->reopenSuccess, 'load' => helper::createLink('mr', 'view', "mr={$MR->id}"));
        return array('result' => 'fail', 'message' => $this->lang->fail, 'load' => helper::createLink('mr', 'view', "mr={$MR->id}"));
    }

    /**
     * 获取合并请求关联的对象。
     * Get mr link list.
     *
     * @param  int    $MRID
     * @param  int    $productID
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkList(int $MRID, int $productID, string $type, string $orderBy = 'id_desc', object $pager = null): array
    {
        $linkIDs = $this->dao->select('BID')->from(TABLE_RELATION)
            ->where('product')->eq($productID)
            ->andWhere('relation')->eq('interrated')
            ->andWhere('AType')->eq('mr')
            ->andWhere('AID')->eq($MRID)
            ->andWhere('BType')->eq($type)
            ->fetchPairs('BID');
        if(empty($linkIDs)) return array();

        $orderBy = str_replace('name_', 'title_', $orderBy);
        if($type == 'story')
        {
            return $this->dao->select('t1.*, t2.spec, t2.verify, t3.name as productTitle')
                ->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.version=t2.version')
                ->andWhere('t1.id')->in($linkIDs)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            if($type == 'task') $orderBy = str_replace('title_', 'name_', $orderBy);
            return $this->dao->select('*')->from($this->config->objectTables[$type])
                ->where('deleted')->eq(0)
                ->andWhere('id')->in($linkIDs)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
    }

    /**
     * 根据对象信息获取合并请求列表。
     * Get linked MR pairs.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getLinkedMRPairs(int $objectID, string $objectType = 'story'): array
    {
        return $this->dao->select("t2.id,t2.title")->from(TABLE_RELATION)->alias('t1')
            ->leftJoin(TABLE_MR)->alias('t2')->on('t1.AID = t2.id')
            ->where('t1.AType')->eq('mr')
            ->andWhere('t1.BType')->eq($objectType)
            ->andWhere('t1.BID')->eq($objectID)
            ->andWhere('t2.id')->ne(0)
            ->fetchPairs();
    }

    /**
     * 获取合并请求的提交记录。
     * Get diff commits of MR.
     *
     * @param  object $MR
     * @access public
     * @return array|object
     */
    public function getDiffCommits(object $MR): array|object
    {
        $host = $this->loadModel('pipeline')->getByID($MR->hostID);
        if($host->type == 'gogs')
        {
            $repo = $this->loadModel('repo')->getByID($MR->repoID);
            $scm  = $this->app->loadClass('scm');
            $scm->setEngine($repo);
            return $scm->getMRCommits($MR->sourceBranch, $MR->targetBranch);
        }
        else
        {
            if($host->type == 'gitlab') $url = sprintf($this->loadModel('gitlab')->getApiRoot($MR->hostID), "/projects/{$MR->targetProject}/merge_requests/{$MR->mriid}/commits");
            if($host->type == 'gitea')  $url = sprintf($this->loadModel('gitea')->getApiRoot($MR->hostID), "/repos/{$MR->targetProject}/pulls/{$MR->mriid}/commits");
            return json_decode(commonModel::http($url));
        }
    }

    /**
     * 合并请求关联对象。
     * Create an mr link.
     *
     * @param  int    $MRID
     * @param  int    $productID
     * @param  string $type
     * @access public
     * @return bool
     */
    public function link(int $MRID, int $productID, string $type): bool
    {
        if($type == 'story') $links = $this->post->stories;
        if($type == 'bug')   $links = $this->post->bugs;
        if($type == 'task')  $links = $this->post->tasks;

        /* Get link action text. */
        $MR             = $this->fetchByID($MRID);
        $users          = $this->loadModel('user')->getPairs('noletter');
        $MRCreateAction = $MR->createdDate . '::' . zget($users, $MR->createdBy) . '::' . helper::createLink('mr', 'view', "mr={$MR->id}");

        $this->loadModel('action');
        foreach($links as $linkID)
        {
            $relation           = new stdclass;
            $relation->product  = $productID;
            $relation->AType    = 'mr';
            $relation->AID      = $MRID;
            $relation->relation = 'interrated';
            $relation->BType    = $type;
            $relation->BID      = $linkID;
            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();

            $this->action->create($type, $linkID, 'createmr', '', $MRCreateAction);
        }

        return !dao::isError();
    }

    /**
     * 保存合并请求关联的对象。
     * Save linked objects.
     *
     * @param  object $MR
     * @access public
     * @return bool
     */
    public function linkObjects(object $MR): bool
    {
        /* Get commits by MR. */
        $commits = $this->apiGetMRCommits($MR->hostID, (string)$MR->targetProject, $MR->mriid);
        if(empty($commits)) return true;

        /* Init objects. */
        $objectList = array();
        $this->loadModel('repo');
        foreach($commits as $commit)
        {
            $objects = $this->repo->parseComment($commit->message);
            $objectList['story'] = array_merge($objectList['stories'], $objects['stories']);
            $objectList['bug']   = array_merge($objectList['bugs'],    $objects['bugs']);
            $objectList['task']  = array_merge($objectList['tasks'],   $objects['tasks']);
        }

        $users          = $this->loadModel('user')->getPairs('noletter');
        $MRCreateAction = $MR->createdDate . '::' . zget($users, $MR->createdBy) . '::' . helper::createLink('mr', 'view', "mr={$MR->id}");
        $product        = $this->getMRProduct($MR);

        $this->loadModel('action');
        foreach($objectList as $type => $objectIDs)
        {
            $relation           = new stdclass();
            $relation->product  = $product->id;
            $relation->AType    = 'mr';
            $relation->AID      = $MR->id;
            $relation->relation = 'interrated';
            $relation->BType    = $type;
            foreach($objectIDs as $objectID)
            {
                $relation->BID = $objectID;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                $this->action->create($type, $objectID, 'createmr', '', $MRCreateAction);
            }
        }
        return !dao::isError();
    }

    /**
     * 解除合并请求关联的对象。
     * Unlink an mr link.
     *
     * @param  int    $MRID
     * @param  int    $productID
     * @param  string $type
     * @param  int    $linkID
     * @access public
     * @return bool
     */
    public function unlink(int $MRID, int $productID, string $type, int $linkID): bool
    {
        $this->dao->delete()->from(TABLE_RELATION)
            ->where('product')->eq($productID)
            ->andWhere('AType')->eq('mr')
            ->andWhere('AID')->eq($MRID)
            ->andWhere('BType')->eq($type)
            ->andWhere('BID')->eq($linkID)
            ->exec();

        $this->loadModel('action')->create($type, $linkID, 'deletemr', '', helper::createLink('mr', 'view', "mr={$MRID}"));
        return !dao::isError();
    }

    /**
     * 获取合并请求的产品。
     * Get mr product.
     *
     * @param  object $MR
     * @access public
     * @return object
     */
    public function getMRProduct(object $MR): object
    {
        $product = new stdclass();
        $product->id = 0;

        $productID = 0;
        if(is_object($MR) && $MR->repoID)
        {
            $productID = $this->dao->select('product')->from(TABLE_REPO)->where('id')->eq($MR->repoID)->fetch('product');
        }
        else
        {
            $products  = $this->loadModel('gitlab')->getProductsByProjects(array($MR->targetProject, $MR->sourceProject));
            $productID = array_shift($products);
        }

        if($productID) $product = $this->loadModel('product')->getById((int)$productID);
        return $product;
    }

    /**
     * 获取合并请求的收件人和抄送人。
     * Get toList and ccList.
     *
     * @param  object $MR
     * @access public
     * @return array
     */
    public function getToAndCcList(object $MR): array
    {
        return array($MR->createdBy, $MR->assignee);
    }

    /**
     * 将合并的操作记录到链接
     * Log merged action to links.
     *
     * @param  object $MR
     * @access public
     * @return bool
     */
    public function logMergedAction(object $MR): bool
    {
        $this->loadModel('action')->create('mr', $MR->id, 'mergedmr');

        $product = $this->getMRProduct($MR);
        foreach(array('story', 'bug', 'task') as $type)
        {
            $objects = $this->getLinkList($MR->id, $product->id, $type);
            foreach($objects as $object)
            {
                $this->action->create($type, $object->id, 'mergedmr', '', helper::createLink('mr', 'view', "mr={$MR->id}"));
            }
        }

        $this->dao->update(TABLE_MR)->data(array('status' => 'merged'))->where('id')->eq($MR->id)->exec();
        return !dao::isError();
    }

    /**
     * 检查是否有相同的未关闭合并请求。
     * Check same opened mr for source branch.
     *
     * @param  int    $hostID
     * @param  string $sourceProject
     * @param  string $sourceBranch
     * @param  string $targetProject
     * @param  string $targetBranch
     * @access public
     * @return array
     */
    public function checkSameOpened(int $hostID, string $sourceProject, string $sourceBranch, string $targetProject, string $targetBranch): array
    {
        if(empty($sourceProject) or empty($sourceBranch) or empty($targetProject) or empty($targetBranch)) return array('result' => 'success');
        if(in_array(true, array(empty($sourceProject), empty($sourceBranch), empty($targetProject), empty($targetBranch)))) return array('result' => 'success');

        if($sourceProject == $targetProject && $sourceBranch == $targetBranch) return array('result' => 'fail', 'message' => $this->lang->mr->errorLang[1]);
        $dbOpenedID = $this->dao->select('id')->from(TABLE_MR)
            ->where('hostID')->eq($hostID)
            ->andWhere('sourceProject')->eq($sourceProject)
            ->andWhere('sourceBranch')->eq($sourceBranch)
            ->andWhere('targetProject')->eq($targetProject)
            ->andWhere('targetBranch')->eq($targetBranch)
            ->andWhere('status')->eq('opened')
            ->andWhere('deleted')->eq('0')
            ->fetch('id');
        if(!empty($dbOpenedID)) return array('result' => 'fail', 'message' => sprintf($this->lang->mr->hasSameOpenedMR, $dbOpenedID));

        $MR = $this->apiGetSameOpened($hostID, (string)$sourceProject, $sourceBranch, (string)$targetProject, $targetBranch);
        if($MR) return array('result' => 'fail', 'message' => sprintf($this->lang->mr->errorLang[2], $MR->iid));
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

        foreach($this->lang->mr->apiErrorMap as $key => $errorMsg)
        {
            if(strpos($errorMsg, '/') === 0)
            {
                $result = preg_match($errorMsg, $message, $matches);
                if($result) $errorMessage = sprintf(zget($this->lang->mr->errorLang, $key), $matches[1]);
            }
            elseif($message == $errorMsg)
            {
                $errorMessage = zget($this->lang->mr->errorLang, $key, $message);
            }
            if(isset($errorMessage)) break;
        }
        return isset($errorMessage) ? $errorMessage : $message;
    }

    /**
     * 判断按钮是否可点击。
     * Adjust the action clickable.
     *
     * @param  object $MR
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $MR, string $action): bool
    {
        if($action == 'edit' and !$MR->synced) return false;
        if($action == 'edit')   return $MR->canEdit != 'disabled';
        if($action == 'delete') return $MR->canDelete != 'disabled';

        return true;
    }

    /**
     * 根据ID删除合并请求。
     * Delete MR by ID.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function deleteByID(int $id): bool
    {
        $MR = $this->fetchByID($id);
        if(!$MR) return false;

        if($MR->synced)
        {
           $res = $this->apiDeleteMR($MR->hostID, $MR->targetProject, $MR->mriid);
           if(isset($res->message)) dao::$errors[] = $this->convertApiError($res->message);
           if(dao::isError()) return false;
        }

        $this->dao->delete()->from(TABLE_MR)->where('id')->eq($id)->exec();

        $this->loadModel('action')->create('mr', $id, 'deleted', '', $MR->title);
        $this->createMRLinkedAction($id, 'removemr');
        return !dao::isError();
    }
}

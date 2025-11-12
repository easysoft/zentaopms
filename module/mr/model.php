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
 * @property    gitlabModel $gitlab
 */
class mrModel extends model
{
    public $moduleName = 'mr';

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
            foreach($filterProjects as $hostID => $projectID)
            {
                $filterProjectSql .= "(hostID = {$hostID} AND sourceProject = '{$projectID}') OR ";
            }

            if($filterProjectSql) $filterProjectSql = '(' . substr($filterProjectSql, 0, -3) . ')'; // Remove last or.
        }

        if($this->app->tab == 'project')
        {
            $executionIdList = $this->loadModel('execution')->fetchExecutionList($objectID, 'all');
            if(!empty($executionIdList)) $objectID = array_merge(array_keys($executionIdList), array($objectID));
        }

        return $this->dao->select('*')->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->beginIF($mode == 'status' && $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($mode == 'assignee' && $param != 'all')->andWhere('assignee')->eq($param)->fi()
            ->beginIF($mode == 'creator' && $param != 'all')->andWhere('createdBy')->eq($param)->fi()
            ->beginIF($filterProjectSql)->andWhere($filterProjectSql)->fi()
            ->beginIF($repoID)->andWhere('repoID')->eq($repoID)->fi()
            ->beginIF($this->moduleName == 'mr')->andWhere('isFlow')->eq('0')->fi()
            ->beginIF($this->moduleName == 'pullreq')->andWhere('isFlow')->eq('1')->fi()
            ->beginIF($objectID && $this->moduleName == 'mr')->andWhere('executionID')->in($objectID)->fi()
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
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function getGitlabProjects(int $hostID = 0, array $projectIdList = array()): array
    {
        if(!$this->app->user->admin)
        {
            $gitlabUsers = $this->loadModel('pipeline')->getProviderPairsByAccount('gitlab');
            if(!isset($gitlabUsers[$hostID])) return array();
        }

        $minProject = $maxProject = 0;
        /* Mysql string to int. */
        $MR = $this->dao->select('min(CAST(sourceProject AS DECIMAL)) as minSource, MAX(CAST(sourceProject AS DECIMAL)) as maxSource,MIN(CAST(targetProject AS DECIMAL)) as minTarget,MAX(CAST(targetProject AS DECIMAL)) as maxTarget')->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('hostID')->eq($hostID)
            ->beginIF($projectIdList)->andWhere('sourceProject', true)->in($projectIdList)
            ->orWhere('targetProject')->in($projectIdList)
            ->markRight(1)
            ->fi()
            ->fetch();
        if(empty($MR->minSource) && empty($MR->minTarget)) return array();

        $minProject = min($MR->minSource, $MR->minTarget);
        $maxProject = max($MR->maxSource, $MR->maxTarget);

        /* If not an administrator, need to obtain group member information. */
        $groupIDList = array(0 => 0);
        $this->loadModel('gitlab');
        if(!$this->app->user->admin)
        {
            $groups = $this->gitlab->apiGetGroups($hostID, 'name_asc', 'reporter');
            foreach($groups as $group) $groupIDList[] = $group->id;
        }

        $allProjectPairs = array();
        $allProjects     = $this->gitlab->apiGetProjects($hostID, 'false', (int)$minProject, (int)$maxProject);
        foreach($allProjects as $project)
        {
            if($projectIdList && !in_array($project->id, $projectIdList)) continue;
            if(!$this->gitlab->checkUserAccess($hostID, 0, $project, $groupIDList, 'reporter')) continue;

            $project->isDeveloper = $this->gitlab->checkUserAccess($hostID, 0, $project, $groupIDList, 'developer');
            $allProjectPairs[$hostID][$project->id] = $project;
        }

        return $allProjectPairs;
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
        $result = $this->checkSameOpened($MR->hostID, $MR->sourceProject, $MR->sourceBranch, $MR->targetProject, $MR->targetBranch);
        if($result['result'] == 'fail') return $result;

        $MRID = $this->insertMr($MR);
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $this->loadModel('action')->create($this->moduleName, $MRID, 'opened');
        if($MR->needCI && $MR->jobID) $this->execJob($MRID, (int)$MR->jobID);

        $rawMR = $this->apiCreateMR($MR->hostID, $MR);

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
        if(empty($MR->jobID)) $this->apiCreateMRTodo($MR->hostID, $MR->targetProject, $rawMR->iid);

        $newMR = new stdclass();
        $newMR->mriid       = $rawMR->iid;
        $newMR->status      = $rawMR->state == 'open' ? 'opened' : $rawMR->state;
        $newMR->mergeStatus = $rawMR->merge_status;
        $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($MRID)->autoCheck()->exec();

        /* Link stories,bugs and tasks. */
        $MR->id    = $MRID;
        $MR->mriid = $newMR->mriid;
        $this->linkObjects($MR);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        $linkParams = $this->app->tab == 'execution' || $this->app->tab == 'project' ? "repoID=0&mode=status&param=opened&objectID={$MR->executionID}" : "repoID={$MR->repoID}";
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => helper::createLink($this->moduleName, 'browse', $linkParams));
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
        $repo     = $this->dao->findByID($postData->repoID)->from(TABLE_REPO)->fetch();
        if(empty($repo))
        {
            dao::$errors[] = 'No matched gitlab.';
            return false;
        }

        /* Process and insert mr data. */
        $MR = new stdClass();
        $MR->hostID         = (int)$repo->serviceHost;
        $MR->sourceProject  = $repo->serviceProject;
        $MR->sourceBranch   = $postData->sourceBranch;
        $MR->targetProject  = $repo->serviceProject;
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

        $MRID = $this->insertMr($MR);
        if(dao::isError()) return false;

        $this->loadModel('action')->create($this->moduleName, $MRID, 'opened');

        /* Exec Job */
        if($MR->hasNoConflict == '0' && $MR->mergeStatus == 'can_be_merged' && $MR->jobID) $this->execJob($MRID, (int)$MR->jobID);
        return $MRID;
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
        $diff  = array_diff_assoc((array)$oldMR, (array)$MR);

        if(!$oldMR) return array('result' => 'fail', 'message' => $this->lang->mr->notFound);

        /* The source and target branches cannot be the same. */
        if($oldMR->sourceBranch == $MR->targetBranch ) return array('result' => 'fail', 'message' => $this->lang->mr->errorLang[1]);

        $this->dao->update(TABLE_MR)->data($MR)->checkIF($MR->needCI, 'jobID',  'notempty');
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        /* Exec Job */
        $needExecJob = isset($diff['targetBranch']) || isset($diff['jobID']) ? true : false;
        if($needExecJob && isset($MR->jobID) && $MR->jobID)
        {
            $this->execJob($MRID, (int)$MR->jobID);
            if(dao::isError()) return array('result' => 'fail', 'message' => implode("\n", dao::getError()));
        }

        /* Known issue: `reviewer_ids` takes no effect. */
        $rawMR = $this->apiUpdateMR($oldMR, $MR);
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
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $MR = $this->fetchByID($MRID);
        $this->linkObjects($MR);

        $actionID = $this->loadModel('action')->create($this->moduleName, $MRID, 'edited');
        $changes  = common::createChanges($oldMR, $MR);
        if(!empty($changes))
        {
            foreach($changes as &$change) if($change['field'] == 'assignee') $change['field'] = 'reviewer';
            $this->action->logHistory($actionID, $changes);
        }
        $this->createMRLinkedAction($MRID, 'edit' . $this->moduleName, $MR->editedDate);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        if($this->session->{$this->app->tab}) $MR->executionID = $this->session->{$this->app->tab};
        $linkParams = $this->app->tab == 'execution' || $this->app->tab == 'project' ? "repoID=0&mode=status&param=opened&objectID={$MR->executionID}" : "repoID={$MR->repoID}";
        return array('result' => 'success', 'message' => $needExecJob ? $this->lang->mr->triggeredCI : $this->lang->saveSuccess, 'load' => helper::createLink($this->moduleName, 'browse', $linkParams));
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

        $MRAction = $actionDate . '::' . $this->app->user->account . '::' . helper::createLink($this->moduleName, 'view', "mr={$MRID}");

        $this->loadModel('action');
        foreach(array('story', 'task', 'bug') as $objectType)
        {
            $linkedObjects = $this->mrTao->getLinkedObjectPairs($MRID, $objectType);
            foreach($linkedObjects as $objectID) $this->action->create($objectType, $objectID, $action, '', $MRAction);
        }
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
        $rawMR = $this->apiGetSingleMR($MR->repoID, $MR->mriid);
        /* Sync MR in ZenTao database whatever status of MR in GitLab. */
        if(isset($rawMR->iid))
        {
            $newMR    = new stdclass();
            $gitUsers = $this->loadModel('pipeline')->getUserBindedPairs($MR->hostID, $rawMR->gitService, 'openID,account');
            foreach($this->config->mr->maps->sync as $syncField => $config)
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
            if(isset($MR->needCI) && $MR->needCI == '1')
            {
                $compile = $this->loadModel('compile')->getByID($MR->id);
                $newMR->compileStatus = $compile ? $compile->status : 'failed';
            }

            /* Update MR in Zentao database. */
            $newMR->editedBy   = $this->app->user->account;
            $newMR->editedDate = helper::now();
            $this->dao->update(TABLE_MR)->data($newMR)
                ->where('id')->eq($MR->id)
                ->exec();
        }
        return $this->fetchByID($MR->id);
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
     * @param  int    $hostID
     * @param  object $MR
     * @access public
     * @return object|null
     */
    public function apiCreateMR(int $hostID, object $MR): object|null
    {
        $repo = $this->loadModel('repo')->getByID($MR->repoID);
        if(!$repo) return null;

        $openID   = $this->loadModel('pipeline')->getOpenIdByAccount($hostID, strtolower($repo->SCM), $this->app->user->account);
        $assignee = '';
        if($MR->assignee) $assignee = $this->pipeline->getOpenIdByAccount($hostID, strtolower($repo->SCM), $MR->assignee);

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $result = $scm->createMR($MR, $openID, $assignee);
        if(!empty($result->message)) $result->message = $this->convertApiError($result->message);
        return $result;
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

        $url      = sprintf($this->loadModel('gitlab')->getApiRoot((int)$hostID), "/projects/{$sourceProject}/merge_requests") . "&state=opened&source_branch={$sourceBranch}&target_branch={$targetBranch}";
        $response = json_decode(commonModel::http($url));
        if($response)
        {
            foreach($response as $MR)
            {
                if(empty($MR->source_project_id) || empty($MR->target_project_id)) return null;
                if($MR->source_project_id == $sourceProject && $MR->target_project_id == $targetProject) return $MR;
            }
        }
        return null;
    }

    /**
     * 通过API获取单个合并请求。
     * Get single MR by API.
     *
     * @param  int    $repoID
     * @param  int    $MRID
     * @access public
     * @return object|null
     */
    public function apiGetSingleMR(int $repoID, int $MRID): object|null
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(!$repo) return null;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $MR = $scm->getSingleMR($MRID);

        if($MR)
        {
            if(!isset($MR->flow)) $MR->flow = 0;
            $MR->gitService = strtolower($repo->SCM);
            if($MR->state == 'open') $MR->state = 'opened';
        }
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
     * @return array|null
     */
    public function apiGetMRCommits(int $hostID, string $projectID, int $MRID): array|null
    {
        $host    = $this->loadModel('pipeline')->getByID($hostID);
        $apiRoot = $this->loadModel($host->type)->getApiRoot($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($apiRoot, "/projects/$projectID/merge_requests/$MRID/commits");
        }
        else
        {
            $url = sprintf($apiRoot, "/repos/$projectID/pulls/$MRID/commits");
        }

        return (array)json_decode(commonModel::http($url, null, array(), is_object($apiRoot) ? $apiRoot->header : array()));
    }

    /**
     * 通过API更新合并请求。
     * Update MR by API.
     *
     * @param  object $oldMR
     * @param  object $MR
     * @access public
     * @return object|null
     */
    public function apiUpdateMR(object $oldMR, object $MR): object|null
    {
        $host = $this->loadModel('pipeline')->getByID($oldMR->hostID);
        if(!$host) return null;

        if(!empty($MR->assignee)) $assignee = $this->pipeline->getOpenIdByAccount($host->id, $host->type, $MR->assignee);

        $apiRoot = $this->loadModel($host->type)->getApiRoot($host->id);
        $MRObject = new stdclass();
        $MRObject->title = $MR->title;
        if($host->type == 'gitlab')
        {
            if(isset($MR->targetBranch))         $MRObject->target_branch        = zget($MR, 'targetBranch', $oldMR->targetBranch);
            if(isset($MR->description))          $MRObject->description          = $MR->description;
            if(isset($MR->remove_source_branch)) $MRObject->remove_source_branch = $MR->removeSourceBranch == '1' ? true : false;
            if(isset($MR->squash))               $MRObject->squash               = $MR->squash == '1' ? 1 : 0;
            if(!empty($assignee))                $MRObject->assignee_ids         = $assignee;

            $url = sprintf($apiRoot, "/projects/{$oldMR->sourceProject}/merge_requests/{$oldMR->mriid}");
            return json_decode(commonModel::http($url, $MRObject, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            if(isset($MR->targetBranch)) $MRObject->base     = zget($MR, 'targetBranch', $oldMR->targetBranch);
            if(isset($MR->description))  $MRObject->body     = $MR->description;
            if(!empty($assignee))        $MRObject->assignee = $assignee;

            $url = sprintf($apiRoot, "/repos/{$oldMR->sourceProject}/pulls/{$oldMR->mriid}");
            $mergeResult = json_decode(commonModel::http($url, $MRObject, array(), array(), 'json', 'PATCH'));

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
        if(!$host) return null;

        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID");
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
        }

        $repoID = $this->dao->select('repoID')->from(TABLE_MR)
            ->where('hostID')->eq($hostID)
            ->andWhere('sourceProject')->eq($projectID)
            ->andWhere('mriid')->eq($MRID)
            ->andWhere('deleted')->eq('0')
            ->fetch('repoID');
        $rowMR  = $this->apiGetSingleMR((int)$repoID, $MRID);
        if($rowMR && $rowMR->state == 'opened')
        {
            $apiRoot = $this->loadModel($host->type)->getApiRoot($hostID);
            $header = is_string($apiRoot) ? array() : $apiRoot->header;

            if(is_object($apiRoot)) $apiRoot = $apiRoot->url;
            $api = "/repos/$projectID/pulls/$MRID";
            return json_decode(commonModel::http(sprintf($apiRoot, $api), array('state' => 'closed'), array(), $header, 'json', 'PATCH'));
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
     * @return object|null
     */
    public function apiCloseMR(int $hostID, string $projectID, int $MRID): object|null
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if(!$host) return null;

        if($host->type == 'gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID") . '&state_event=close';
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            return $this->apiDeleteMR($hostID, $projectID, $MRID);
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
     * @return object|null
     */
    public function apiReopenMR(int $hostID, string $projectID, int $MRID): object|null
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if(!$host) return null;

        $apiRoot = $this->loadModel($host->type)->getApiRoot($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($apiRoot, "/projects/$projectID/merge_requests/$MRID") . '&state_event=reopen';
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $url = sprintf($apiRoot, "/repos/$projectID/pulls/$MRID");
            return json_decode(commonModel::http($url, array('state' => 'open'), array(), array(), 'json', 'PATCH'));
        }
    }

    /**
     * 通过API接受合并请求。
     * Accept MR by API.
     *
     * @param  object $MR
     * @access public
     * @return object|null
     */
    public function apiAcceptMR(object $MR): object|null
    {
        $host = $this->loadModel('pipeline')->getByID($MR->hostID);
        if(!$host) return null;

        $apiRoot = $this->loadModel($host->type)->getApiRoot($MR->hostID);
        if($host->type == 'gitlab')
        {
            $approveUrl = sprintf($apiRoot, "/projects/$MR->targetProject/merge_requests/$MR->mriid/approved");
            commonModel::http($approveUrl, null, array(CURLOPT_CUSTOMREQUEST => 'POST'));

            $url = sprintf($apiRoot, "/projects/$MR->targetProject/merge_requests/$MR->mriid/merge");
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $url  = sprintf($apiRoot, "/repos/$MR->targetProject/pulls/$MR->mriid/merge");
            $data = array('Do' => $MR->squash == '1' ? 'squash' : 'merge');
            if($MR->removeSourceBranch == '1') $data['delete_branch_after_merge'] = true;
        }

        $rowMR = json_decode(commonModel::http($url, $data, array(), array(), 'json', 'POST'));
        if(!isset($rowMR->massage))
        {
            $rowMR = $this->apiGetSingleMR($MR->repoID, $MR->mriid);
            if($MR->removeSourceBranch == '1' && $host->type == 'gogs') $this->loadModel($host->type)->apiDeleteBranch($MR->hostID, $MR->sourceProject, $MR->sourceBranch);
        }

        return $rowMR;
    }

    /**
     * 获取合并请求的对比信息。
     * Get MR diff versions by API.
     *
     * @param  object $MR
     * @access public
     * @return array
     */
    public function getDiffs(object $MR): array
    {
        if(!isset($MR->repoID)) return array();

        $repo = $this->loadModel('repo')->getByID($MR->repoID);
        if(!$repo) return array();

        $host = $this->loadModel('pipeline')->getByID($MR->hostID);
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

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
            $lines = $this->apiGetDiffs($MR->hostID, $MR->targetProject, $MR->mriid);
        }

        if(empty($MR->synced)) $lines = preg_replace('/^\s*$\n?\r?/m', '', $MR->diffs);

        if(is_string($lines)) $lines = explode("\n", $lines);
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
     * @return object|null
     */
    public function apiCreateMRTodo(int $hostID, string $projectID, int $MRID): object|null
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
     * @return array|null
     */
    public function apiGetDiffVersions(int $hostID, string $projectID, int $MRID): array|null
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
     * @return object|null
     */
    public function apiGetSingleDiffVersion(int $hostID, string $projectID, int $MRID, int $versionID): object|null
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
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if(!$host || $host->type == 'gitlab') return '';

        $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID.diff");
        return commonModel::http($url);
    }

    /**
     * 审核合并请求。
     * Reject or Approve this MR.
     *
     * @param  object $MR
     * @param  string $action  approve|reject
     * @param  string $comment
     * @return array
     */
    public function approve(object $MR, string $action = 'approve', string $comment = ''): array
    {
        if(isset($MR->status) && $MR->status == 'opened')
        {
            $oldMR = clone $MR;
            $rawApprovalStatus = zget($MR, 'approvalStatus', '');
            if($action == 'reject'  && $rawApprovalStatus != 'rejected') $MR->approvalStatus = 'rejected';
            if($action == 'approve' && $rawApprovalStatus != 'approved') $MR->approvalStatus = 'approved';
            if(isset($MR->approvalStatus) && $rawApprovalStatus != $MR->approvalStatus)
            {
                $changes = common::createChanges($oldMR, $MR);

                unset($MR->editedDate);
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

                $actionID = $this->loadModel('action')->create($this->moduleName, $MR->id, $action);
                $this->action->logHistory($actionID, $changes);
                return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true);
            }
        }
        return array('result' => 'fail', 'message' => $this->lang->mr->repeatedOperation, 'load' => helper::createLink($this->moduleName, 'view', "mr={$MR->id}"));
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
        if($MR->status == 'closed') return array('result' => 'fail', 'message' => $this->lang->mr->repeatedOperation);

        $actionID = $this->loadModel('action')->create($this->moduleName, $MR->id, 'closed');
        $rawMR    = $this->apiCloseMR($MR->hostID, $MR->targetProject, $MR->mriid);
        $changes  = common::createChanges($MR, $rawMR);
        $this->action->logHistory($actionID, $changes);

        if(isset($rawMR->state) && $rawMR->state == 'closed') return array('result' => 'success', 'message' => $this->lang->mr->closeSuccess, 'load' => 'reload');
        return array('result' => 'fail', 'message' => $this->lang->fail);
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
        if($MR->status == 'opened') return array('result' => 'fail', 'message' => $this->lang->mr->repeatedOperation);

        $this->loadModel('action')->create($this->moduleName, $MR->id, 'reopen');
        $rawMR = $this->apiReopenMR($MR->hostID, $MR->targetProject, $MR->mriid);

        if(!empty($rawMR) && empty($rawMR->message)) return array('result' => 'success', 'message' => $this->lang->mr->reopenSuccess, 'load' => 'reload');
        return array('result' => 'fail', 'message' => $this->lang->fail);
    }

    /**
     * 获取合并请求关联的对象。
     * Get mr link list.
     *
     * @param  int    $MRID
     * @param  string $type       story|task|bug
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLinkList(int $MRID, string $type, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if(!isset($this->config->objectTables[$type])) return array();

        $orderBy = str_replace('name_', 'title_', $orderBy);
        if($type == 'task') $orderBy = str_replace('title_', 'name_', $orderBy);

        return $this->dao->select('t1.*')->from($this->config->objectTables[$type])->alias('t1')
            ->leftJoin(TABLE_RELATION)->alias('t2')->on('t1.id=t2.BID')
            ->where('t2.relation')->eq('interrated')
            ->andWhere('t2.AType')->eq($this->moduleName)
            ->andWhere('t2.AID')->eq($MRID)
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
            ->leftJoin(TABLE_MR)->alias('t2')->on('t1.AID = t2.id')
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
     * @param  int    $MRID
     * @param  string $type       story|task|bug
     * @param  array  $objects
     * @access public
     * @return bool
     */
    public function link(int $MRID, string $type, array $objects): bool
    {
        if(!isset($this->config->objectTables[$type])) return false;

        $MR = $this->fetchByID($MRID);
        if(!$MR) return false;

        /* Set link action text. */
        $user    = $this->loadModel('user')->getRealNameAndEmails($MR->createdBy);
        $comment = $MR->createdDate . '::' . zget($user, 'realname', $this->app->user->realname) . '::' . helper::createLink($this->moduleName, 'view', "mr={$MR->id}");

        $this->loadModel('action');
        foreach($objects as $objectID)
        {
            $relation = new stdclass();
            $relation->product  = 0;
            $relation->AType    = $this->moduleName;
            $relation->AID      = $MRID;
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
        $MRCreateAction = $MR->createdDate . '::' . zget($users, $MR->createdBy) . '::' . $MR->id;
        $product        = $this->getMRProduct($MR);

        $this->loadModel('action');
        foreach($objectList as $type => $objectIDs)
        {
            $relation           = new stdclass();
            $relation->product  = $product ? $product->id : 0;
            $relation->AType    = $this->moduleName;
            $relation->AID      = $MR->id;
            $relation->relation = 'interrated';
            $relation->BType    = $type;
            foreach($objectIDs as $objectID)
            {
                $relation->BID = $objectID;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                $this->action->create($type, (int)$objectID, 'create' . $this->moduleName, '', $MRCreateAction);
            }
        }
        return !dao::isError();
    }

    /**
     * 解除合并请求关联的对象。
     * Unlink an mr link.
     *
     * @param  int    $MRID
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function unlink(int $MRID, string $type, int $objectID): bool
    {
        if(!isset($this->config->objectTables[$type])) return false;

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->eq($this->moduleName)
            ->andWhere('AID')->eq($MRID)
            ->andWhere('relation')->eq('interrated')
            ->andWhere('BType')->eq($type)
            ->andWhere('BID')->eq($objectID)
            ->exec();

        $this->loadModel('action')->create($type, $objectID, 'deletemr', '', helper::createLink($this->moduleName, 'view', "mr={$MRID}"));
        return !dao::isError();
    }

    /**
     * 获取合并请求的产品。
     * Get mr product.
     *
     * @param  object $MR
     * @access public
     * @return object|false
     */
    public function getMRProduct(object $MR): object|false
    {
        $productID = $this->dao->select('product')->from(TABLE_REPO)->where('id')->eq($MR->repoID)->fetch('product');
        if(!$productID) return false;

        return $this->loadModel('product')->getById((int)$productID);
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
        $this->loadModel('action')->create($this->moduleName, $MR->id, 'merged' . $this->moduleName);

        foreach(array('story', 'bug', 'task') as $type)
        {
            $objects = $this->getLinkList($MR->id, $type);
            foreach($objects as $object)
            {
                $this->action->create($type, $object->id, 'merged' . $this->moduleName, '', helper::createLink($this->moduleName, 'view', "mr={$MR->id}"));
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
                if($result) $errorMessage = sprintf(zget($this->lang->mr->errorLang, $key), zget($matches, 1, $matches[0]));
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
        if($action == 'edit' && !$MR->synced) return false;

        if($action == 'edit')   return $MR->canEdit != 'disabled';
        if($action == 'delete') return $MR->canDelete != 'disabled';

        return true;
    }

    /**
     * 根据ID删除合并请求。
     * Delete MR by ID.
     *
     * @param  int    $MRID
     * @access public
     * @return bool
     */
    public function deleteByID(int $MRID): bool
    {
        $MR = $this->fetchByID($MRID);
        if(!$MR) return false;

        if($MR->synced)
        {
           $res = $this->apiDeleteMR($MR->hostID, $MR->targetProject, $MR->mriid);
           if(isset($res->message)) dao::$errors[] = $this->convertApiError($res->message);
           if(dao::isError()) return false;
        }

        $this->dao->delete()->from(TABLE_MR)->where('id')->eq($MRID)->exec();

        $this->loadModel('action')->create($this->moduleName, $MRID, 'deleted', '', $MR->title);
        $this->createMRLinkedAction($MRID, 'remove' . $this->moduleName);
        return !dao::isError();
    }

    /**
     * 执行合并请求流水线。
     * Execute MR pipeline.
     *
     * @param  int    $MRID
     * @param  int    $jobID
     * @access public
     * @return bool
     */
    public function execJob(int $MRID, int $jobID): bool
    {
        if(empty($MRID) || empty($jobID)) return false;

        $MR = $this->fetchByID($MRID);
        if(!$MR) return false;

        $compile = $this->loadModel('job')->exec($jobID, array('sourceBranch' => $MR->sourceBranch, 'targetBranch' => $MR->targetBranch), 'commit');
        if(!$compile) return false;

        $newMR = new stdclass();
        $newMR->compileID     = $compile->id;
        $newMR->compileStatus = $compile->status;
        if($newMR->compileStatus == 'failure')     $newMR->status = 'closed';
        if($newMR->compileStatus == 'create_fail') $newMR->status = 'closed';
        $this->loadModel('repo')->saveRelation($MRID, 'mr', $compile->id, 'compile', 'mrjob');

        $this->dao->update(TABLE_MR)->data($newMR)->where('id')->eq($MRID)->autoCheck()->exec();
        return dao::isError();
    }

    /**
     * 创建合并请求。
     * Insert a merge request.
     *
     * @param  object $MR
     * @access public
     * @return int|false
     */
    public function insertMr(object $MR): int|false
    {
        $this->dao->insert(TABLE_MR)->data($MR, $this->config->mr->create->skippedFields)
            ->batchCheck($this->config->mr->create->requiredFields, 'notempty')
            ->checkIF(!empty($MR->needCI), 'jobID',  'notempty')
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }
}

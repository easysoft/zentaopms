<?php
/**
 * The model file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     mr
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class mrModel extends model
{
    /**
     * The construct method, to do some auto things.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('gitlab');
    }

    /**
     * Get a MR by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->findByID($id)->from(TABLE_MR)->fetch();
    }

    /**
     * Get MR list of gitlab project.
     *
     * @param  string     $mode
     * @param  string     $param
     * @param  string     $orderBy
     * @param  object     $pager
     * @param  array|bool $filterProjects
     * @param  int        $repoID
     * @access public
     * @return array
     */
    public function getList($mode = 'all', $param = 'all', $orderBy = 'id_desc', $pager = null, $filterProjects = array(), $repoID = 0)
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

        $MRList = $this->dao->select('*')
            ->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->beginIF($mode == 'status' and $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($mode == 'assignee' and $param != 'all')->andWhere('assignee')->eq($param)->fi()
            ->beginIF($mode == 'creator' and $param != 'all')->andWhere('createdBy')->eq($param)->fi()
            ->beginIF($filterProjectSql)->andWhere($filterProjectSql)->fi()
            ->beginIF($repoID)->andWhere('repoID')->eq($repoID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $MRList;
    }

    /**
     * Get gitlab pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs($repoID)
    {
        $MR = $this->dao->select('id,title')
            ->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('repoID')->eq($repoID)
            ->orderBy('id')->fetchPairs('id', 'title');
        return $MR;
    }

    /**
     * Get all gitlab server projects. If not an administrator, the role of project member should be higher than guest.
     *
     * @param  int    $repoID
     * @param  string $scm
     * @access public
     * @return array
     */
    public function getAllProjects($repoID = 0, $scm = 'Gitlab')
    {
        $hostID = $this->dao->select('hostID')->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('repoID')->eq($repoID)
            ->fetch('hostID');

        return $this->{'get' . $scm . 'Projects'}($hostID);
    }

    /**
     * Get gitea projects.
     *
     * @param  int    $hostID
     * @access public
     * @return array
     */
    public function getGiteaProjects($hostID = 0)
    {
        $projects = $this->loadModel('gitea')->apiGetProjects($hostID);
        return array($hostID => helper::arrayColumn($projects, null, 'full_name'));
    }

    /**
     * Get gogs projects.
     *
     * @param  int    $hostID
     * @access public
     * @return array
     */
    public function getGogsProjects($hostID = 0)
    {
        $projects = $this->loadModel('gogs')->apiGetProjects($hostID);
        return array($hostID => helper::arrayColumn($projects, null, 'full_name'));
    }

    /**
     * Get gitlab projects.
     *
     * @param  int    $hostID
     * @param  array  $projectIds
     * @access public
     * @return array
     */
    public function getGitlabProjects($hostID = 0, $projectIds = array())
    {
        $allProjects = array();
        $allGroups   = array();
        $gitlabUsers = $this->loadModel('gitlab')->getGitLabListByAccount();
        if(!$this->app->user->admin and !isset($gitlabUsers[$hostID])) return array();

        $minProject = $maxProject = 0;
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

        if($projectIds)
        {
            foreach($projectIds as $projectID)
            {
                $project = $this->gitlab->apiGetSingleProject($hostID, $projectID);
                if(isset($project->id)) $allProjects[$hostID][] = $project;
            }
        }
        else
        {
            $allProjects[$hostID] = $this->gitlab->apiGetProjects($hostID, 'false', $minProject, $maxProject);
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
            foreach($projects as $key => $project)
            {
                if($this->gitlab->checkUserAccess($hostID, 0, $project, $allGroups[$hostID], 'reporter') == false) continue;
                $project->isDeveloper = $this->gitlab->checkUserAccess($hostID, 0, $project, $allGroups[$hostID], 'developer');

                $allProjectPairs[$hostID][$project->id] = $project;
            }
        }

        return $allProjectPairs;
    }

    /**
     * Create MR function.
     *
     * @access public
     * @return int|bool|object
     */
    public function create()
    {
        $MR = fixer::input('post')
            ->setDefault('jobID', 0)
            ->setDefault('repoID', 0)
            ->setDefault('sourceProject,targetProject', 0)
            ->setDefault('sourceBranch,targetBranch', '')
            ->setDefault('removeSourceBranch','0')
            ->setDefault('needCI', 0)
            ->setDefault('squash', 0)
            ->setIF($this->post->needCI == 0, 'jobID', 0)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        $result = $this->checkSameOpened($MR->hostID, $MR->sourceProject, $MR->sourceBranch, $MR->targetProject, $MR->targetBranch);
        if($result['result'] == 'fail') return $result;

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

        $this->dao->insert(TABLE_MR)->data($MR, $this->config->mr->create->skippedFields)
            ->batchCheck($this->config->mr->create->requiredFields, 'notempty')
            ->checkIF($MR->needCI, 'jobID',  'notempty')
            ->exec();
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $MRID = $this->dao->lastInsertId();
        $this->loadModel('action')->create('mr', $MRID, 'opened');

        $rawMR = $this->apiCreateMR($this->post->hostID, $this->post->sourceProject, $MR);

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
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => helper::createLink('mr', 'browse'));
    }

    /**
     * Create MR function by api.
     *
     * @access public
     * @return int|bool|object
     */
    public function apiCreate()
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

        if($MR->sourceProject == $MR->targetProject and $MR->sourceBranch == $MR->targetBranch)
        {
            dao::$errors[] = $this->lang->mr->errorLang[1];
            return false;
        }

        $result = $this->checkSameOpened($MR->hostID, $MR->sourceProject, $MR->sourceBranch, $MR->targetProject, $MR->targetBranch);
        if($result['result'] == 'fail')
        {
            dao::$errors[] = $result['message'];
            return false;
        }

        $this->dao->insert(TABLE_MR)->data($MR, $this->config->mr->create->skippedFields)
            ->batchCheck($this->config->mr->apicreate->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $MRID = $this->dao->lastInsertId();
        $this->loadModel('action')->create('mr', $MRID, 'opened');

        /* Exec Job */
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
     * Edit MR function.
     *
     * @access public
     * @return void
     */
    public function update($MRID)
    {
        $MR = fixer::input('post')
            ->setDefault('jobID', 0)
            ->setDefault('compileID', 0)
            ->setDefault('repoID', 0)
            ->setDefault('removeSourceBranch','0')
            ->setDefault('needCI', 0)
            ->setDefault('squash', 0)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', helper::now())
            ->setIF($this->post->needCI == 0, 'jobID', 0)
            ->get();
        $oldMR = $this->getByID($MRID);

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

        $MR = $this->getByID($MRID);
        $this->linkObjects($MR);
        $changes = common::createChanges($oldMR, $MR);
        $actionID = $this->loadModel('action')->create('mr', $MRID, 'edited');
        if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        $this->createMRLinkedAction($MRID, 'editmr', $MR->editedDate);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => helper::createLink('mr', 'browse'));
    }

    /**
     * Create createmr,editmr,removemr action.
     *
     * @param  int    $MRID
     * @param  string $action  createmr|editmr|removemr
     * @param  string $actionDate
     * @access public
     * @return void
     */
    public function createMRLinkedAction($MRID, $action, $actionDate = '')
    {
        $this->loadModel('action');
        if(empty($actionDate)) $actionDate = helper::now();

        $MRAction = $actionDate . '::' . $this->app->user->account . '::' . helper::createLink('mr', 'view', "mr={$MRID}");

        $linkedStories = $this->getLinkedObjectPairs($MRID, 'story');
        $linkedTasks   = $this->getLinkedObjectPairs($MRID, 'task');
        $linkedBugs    = $this->getLinkedObjectPairs($MRID, 'bug');

        foreach($linkedStories as $storyID) $this->action->create('story', $storyID, $action, '', $MRAction);
        foreach($linkedTasks as $taskID)    $this->action->create('task', $taskID, $action, '', $MRAction);
        foreach($linkedBugs as $bugID)      $this->action->create('bug', $bugID, $action, '', $MRAction);
    }

    /**
     * sync MR from GitLab API to Zentao database.
     *
     * @param  object  $MR
     * @access public
     * @return void
     */
    public function apiSyncMR($MR)
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

            /* Update MR in Zentao database. */
            $this->dao->update(TABLE_MR)->data($newMR)
                ->where('id')->eq($MR->id)
                ->exec();
        }
        return $this->dao->findByID($MR->id)->from(TABLE_MR)->fetch();
    }

    /**
     * Batch Sync GitLab MR Database.
     *
     * @param  object $MRList
     * @param  string $scm
     * @access public
     * @return array
     */
    public function batchSyncMR($MRList, $scm = 'Gitlab')
    {
        if(empty($MRList)) return array();

        $this->loadModel('gitlab');
        $this->loadModel('gitea');
        $this->loadModel('gogs');
        foreach($MRList as $key => $MR)
        {
            if($MR->status != 'opened') continue;

            if(!isset($rawMRList[$MR->hostID][$MR->targetProject])) $rawMRList[$MR->hostID][$MR->targetProject] = $this->apiGetMRList($MR->hostID, $MR->targetProject, $scm);
            $rawMR = new stdClass();
            foreach($rawMRList[$MR->hostID][$MR->targetProject] as $projcetRawMR)
            {
                if(isset($projcetRawMR->iid) and $projcetRawMR->iid == $MR->mriid)
                {
                    $rawMR = $projcetRawMR;
                    break;
                }
            }

            if(isset($rawMR->iid))
            {
                /* create gitlab mr todo to zentao todo */
                if($scm == 'Gitlab') $this->batchSyncTodo($MR->hostID, $MR->targetProject);

                $map   = $this->config->mr->maps->sync;
                if($scm == 'Gitlab')
                {
                    $users = $this->gitlab->getUserIdAccountPairs($MR->hostID);
                }
                else
                {
                    $scm   = strtolower($scm);
                    $users = $this->$scm->getUserAccountIdPairs($MR->hostID, 'openID,account');
                }

                $newMR = new stdclass;

                foreach($map as $syncField => $config)
                {
                    $value = '';
                    list($field, $optionType, $options) = explode('|', $config);

                    if($optionType == 'field') $value = $rawMR->$field;
                    if($optionType == 'userPairs')
                    {
                        $gitlabUserID = '';
                        if(isset($rawMR->$field))
                        {
                            $values = $rawMR->$field;
                            if(isset($values[0])) $gitlabUserID = $values[0]->$options;
                        }
                        $value = zget($users, $gitlabUserID, '');
                    }

                    if($value) $newMR->$syncField = $value;
                }

                /* For compatibility with PHP 5.4 . */
                $condition = (array)$newMR;
                if(empty($condition)) continue;

                /* Update compile status of current MR object */
                if(isset($MR->needCI) and $MR->needCI == '1')
                {
                    $newMR->compileStatus = empty($MR->compileID) ? 'failed' : $this->loadModel('compile')->getByID($MR->compileID)->status;
                }

                /* Update MR in Zentao database. */
                $this->dao->update(TABLE_MR)->data($newMR)
                    ->where('id')->eq($MR->id)
                    ->exec();

                /* Refetch MR in Zentao database. */
                $MR = $this->dao->findByID($MR->id)->from(TABLE_MR)->fetch();
                $MRList[$key] = $MR;
            }
        }

        return $MRList;
    }

    /**
     * Sync GitLab Todo to ZenTao Todo.
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchSyncTodo($hostID, $projectID)
    {
        /* It can only get todo from GitLab API by its assignee. So here should use sudo as the assignee to get the todo list. */
        /* In this case, ignore sync todo for reviewer due to an issue in GitLab API. */
        $accountList = $this->dao->select('assignee')->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('status')->eq('opened')
            ->andWhere('hostID')->eq($hostID)
            ->andWhere('targetProject')->eq($projectID)
            ->fetchPairs();

        foreach($accountList as $account)
        {
            $accountPair = $this->getSudoAccountPair($hostID, $projectID, $account);
            if(!empty($accountPair) and isset($accountPair[$account]))
            {
                $sudo  = $accountPair[$account];
                $todoList = $this->gitlab->apiGetTodoList($hostID, $projectID, $sudo);

                foreach($todoList as $rawTodo)
                {
                    $todoDesc = $this->dao->select('*')
                        ->from(TABLE_TODO)
                        ->where('idvalue')->eq($rawTodo->id)
                        ->fetch();
                    if(empty($todoDesc))
                    {
                        $acountPairs = $this->gitlab->getUserIdRealnamePairs($hostID);
                        $author      = isset($acountPairs[$rawTodo->author->id]) ? $acountPairs[$rawTodo->author->id] : $rawTodo->author->name;

                        $todo = new stdClass;
                        $todo->account      = $this->app->user->account;
                        $todo->assignedTo   = $account;
                        $todo->assignedBy   = $this->app->user->account;
                        $todo->date         = date("Y-m-d", strtotime($rawTodo->target->created_at));
                        $todo->assignedDate = $rawTodo->target->created_at;
                        $todo->begin        = '2400'; /* 2400 means begin is 'undefined'. */
                        $todo->end          = '2400'; /* 2400 means end is 'undefined'. */
                        $todo->type         = 'custom';
                        $todo->idvalue      = $rawTodo->id;
                        $todo->pri          = 3;
                        $todo->name         = $this->lang->mr->common . ": " . $rawTodo->target->title;
                        $todo->desc         = $author . '&nbsp;' . $this->lang->mr->at . '&nbsp;' . '<a href="' . $this->gitlab->apiGetSingleProject($hostID, $projectID)->web_url . '" target="_blank">' . $rawTodo->project->path .'</a>' . '&nbsp;' . $this->lang->mr->todomessage . '<a href="' . $rawTodo->target->web_url . '" target="_blank">' . '&nbsp;' . $this->lang->mr->common .'</a>' . '。';
                        $todo->status       = 'wait';
                        $todo->finishedBy   = '';

                        $this->dao->insert(TABLE_TODO)->data($todo)->exec();
                    }
                }
            }
        }
    }

    /**
     * Get a list of todo items.
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function todoDescriptionLink($hostID, $projectID)
    {
        $gitlab = $this->gitlab->getByID($hostID);
        if(!$gitlab) return '';
        return rtrim($gitlab->url, '/')."/dashboard/todos?project_id=$projectID&type=MergeRequest";
    }

    /**
     * Create MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#create-mr
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiCreateMR($hostID, $projectID, $MR)
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
     * Get MR list by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#list-project-merge-requests
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  string $scm
     * @access public
     * @return object
     */
    public function apiGetMRList($hostID, $projectID, $scm = 'Gitlab')
    {
        if($scm == 'Gitlab')
        {
            $url = sprintf($this->loadModel('gitlab')->getApiRoot($hostID), "/projects/$projectID/merge_requests");
        }
        else
        {
            $url = sprintf($this->loadModel('gitea')->getApiRoot($hostID), "/repos/$projectID/pulls");
        }

        $response = json_decode(commonModel::http($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = false));
        if(empty($response) || isset($response->message)) $response = array();
        if($scm == 'Gitea')
        {
            foreach($response as $MR)
            {
                if(empty($MR)) continue;
                $MR->iid   = $MR->number;
                $MR->state = $MR->state == 'open' ? 'opened' : $MR->state;
                if($MR->merged) $MR->state = 'merged';

                $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
                $MR->description       = $MR->body;
                $MR->target_branch     = $MR->base->ref;
                $MR->source_branch     = $MR->head->ref;
                $MR->source_project_id = $projectID;
                $MR->target_project_id = $projectID;
            }
        }
        elseif($scm == 'Gogs')
        {
            foreach($response as $MR)
            {
                $MR->iid   = $MR->id;
                $MR->state = $MR->state == 'open' ? 'opened' : $MR->state;
                if($MR->merged) $MR->state = 'merged';

                $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
                $MR->description       = $MR->body;
                $MR->target_branch     = $MR->base_branch;
                $MR->source_branch     = $MR->head_branch;
                $MR->source_project_id = $projectID;
                $MR->target_project_id = $projectID;
            }
        }

        return $response;
    }

    /**
     * Get same opened mr by api.
     *
     * @param  int    $hostID
     * @param  int    $sourceProject
     * @param  string $sourceBranch
     * @param  int    $targetProject
     * @param  string $targetBranch
     * @access public
     * @return object
     */
    public function apiGetSameOpened($hostID, $sourceProject, $sourceBranch, $targetProject, $targetBranch)
    {
        if(empty($hostID) or empty($sourceProject) or empty($sourceBranch) or  empty($targetProject) or  empty($targetBranch)) return null;

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
     * Get single MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#get-single-mr
     * @param  int    $hostID
     * @param  int    $projectID  targetProject
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetSingleMR($hostID, $projectID, $MRID)
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->gitlab->getApiRoot($hostID, false), "/projects/$projectID/merge_requests/$MRID");
            $MR  = json_decode(commonModel::http($url));
        }
        elseif($host->type == 'gitea')
        {
            $url = sprintf($this->loadModel('gitea')->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            $MR  = json_decode(commonModel::http($url));
            if(isset($MR->url))
            {
                $diff = $this->apiGetDiffs($hostID, $projectID, $MRID);

                $MR->web_url = $MR->url;
                $MR->iid     = $MR->number;
                $MR->state   = $MR->state == 'open' ? 'opened' : $MR->state;
                if($MR->merged) $MR->state = 'merged';

                $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
                $MR->changes_count     = empty($diff) ? 0 : 1;
                $MR->description       = $MR->body;
                $MR->target_branch     = $MR->base->ref;
                $MR->source_branch     = $MR->head->ref;
                $MR->source_project_id = $projectID;
                $MR->target_project_id = $projectID;
                $MR->has_conflicts     = empty($diff) ? true : false;
            }
        }
        elseif($host->type == 'gogs')
        {
            $url = sprintf($this->loadModel('gogs')->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            $MR  = json_decode(commonModel::http($url));
            if(isset($MR->html_url))
            {
                $diff = $this->apiGetDiffs($hostID, $projectID, $MRID);

                $MR->web_url = $MR->html_url;
                $MR->iid     = $MR->id;
                $MR->state   = $MR->state == 'open' ? 'opened' : $MR->state;
                if($MR->merged) $MR->state = 'merged';

                $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
                $MR->changes_count     = empty($diff) ? 0 : 1;
                $MR->description       = $MR->body;
                $MR->target_branch     = $MR->base_branch;
                $MR->source_branch     = $MR->head_branch;
                $MR->source_project_id = $projectID;
                $MR->target_project_id = $projectID;
                $MR->has_conflicts     = empty($diff) ? true : false;
            }
        }

        $MR->gitService = $host->type;
        return $MR;
    }

    /**
     * Get MR commits by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#get-commits
     * @param  int    $hostID
     * @param  int    $projectID  targetProject
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetMRCommits($hostID, $projectID, $MRID)
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/commits");
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID/commits");
        }

        return json_decode(commonModel::http($url));
    }

    /**
     * Update MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiUpdateMR($hostID, $projectID, $MRID, $MR)
    {
        $host  = $this->loadModel('pipeline')->getByID($hostID);
        $newMR = new stdclass;
        $newMR->title = $MR->title;
        if($host->type == 'gitlab')
        {
            $newMR->description          = $MR->description;
            $newMR->target_branch        = $MR->targetBranch;
            $newMR->remove_source_branch = $MR->removeSourceBranch == '1' ? true : false;
            $newMR->squash               = $MR->squash == '1' ? 1 : 0;
            if($MR->assignee)
            {
                $gitlabAssignee = $this->gitlab->getUserIDByZentaoAccount($hostID, $MR->assignee);
                if($gitlabAssignee) $newMR->assignee_ids = $gitlabAssignee;
            }
            $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID");
            return json_decode(commonModel::http($url, $newMR, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");

            $newMR->base = $MR->targetBranch;
            $newMR->body = $MR->description;
            if($MR->assignee)
            {
                $assignee = $this->{$host->type}->getUserIDByZentaoAccount($this->post->hostID, $MR->assignee);
                if($assignee) $newMR->assignee = $assignee;
            }
            $mergeResult = json_decode(commonModel::http($url, $newMR, array(), array(), 'json', 'PATCH'));
            if(isset($mergeResult->number)) $mergeResult->iid = $host->type == 'gitea' ? $mergeResult->number : $mergeResult->id;
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
     * Delete MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#delete-a-merge-request
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiDeleteMR($hostID, $projectID, $MRID)
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
                $url = sprintf($this->loadModel('gitea')->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
                return json_decode(commonModel::http($url, array('state' => 'closed'), array(), array(), 'json', 'PATCH'));
            }

            return null;
        }
    }

     /**
     * Close MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiCloseMR($hostID, $projectID, $MRID)
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID") . '&state_event=close';
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        else
        {
            $url = sprintf($this->loadModel($host->type)->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            return json_decode(commonModel::http($url, array('state' => 'closed'), array(), array(), 'json', 'PATCH'));
        }
    }

    /**
     * Reopen MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiReopenMR($hostID, $projectID, $MRID)
    {
        $host = $this->loadModel('pipeline')->getByID($hostID);
        if($host->type == 'gitlab')
        {
            $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID") . '&state_event=reopen';
            return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
        }
        elseif($host->type == 'gitea')
        {
            $url = sprintf($this->loadModel('gitea')->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            $MR  = json_decode(commonModel::http($url, array('state' => 'open'), array(), array(), 'json', 'PATCH'));
            $MR->iid   = $MR->number;
            $MR->state = $MR->state == 'open' ? 'opened' : $MR->state;
            if($MR->merged) $MR->state = 'merged';

            $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
            $MR->description       = $MR->body;
            $MR->target_branch     = $MR->base->ref;
            $MR->source_branch     = $MR->head->ref;
            $MR->source_project_id = $projectID;
            $MR->target_project_id = $projectID;

            return $MR;
        }
        elseif($host->type == 'gogs')
        {
            $url = sprintf($this->loadModel('gitea')->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID");
            $MR  = json_decode(commonModel::http($url, array('state' => 'open'), array(), array(), 'json', 'PATCH'));
            $MR->iid   = $MR->id;
            $MR->state = $MR->state == 'open' ? 'opened' : $MR->state;
            if($MR->merged) $MR->state = 'merged';

            $MR->merge_status      = $MR->mergeable ? 'can_be_merged' : 'cannot_be_merged';
            $MR->description       = $MR->body;
            $MR->target_branch     = $MR->base_branch;
            $MR->source_branch     = $MR->head_branch;
            $MR->source_project_id = $projectID;
            $MR->target_project_id = $projectID;

            return $MR;
        }
    }

    /**
     * Accept MR by API.
     *
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiAcceptMR($MR)
    {
        $host = $this->loadModel('pipeline')->getByID($MR->hostID);
        if($host->type == 'gitlab')
        {
            $apiRoot    = $this->gitlab->getApiRoot($MR->hostID);
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
     * Get MR diff versions by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#get-mr-diff-versions
     * @param  object    $MR
     * @param  string    $encoding
     * @access public
     * @return object
     */
    public function getDiffs($MR, $encoding = '')
    {
        $diffVersions = array();

        $host = $this->loadModel('pipeline')->getByID($MR->hostID);
        $scm  = $host->type;

        $repo = $this->loadModel('repo')->getByID($MR->repoID);
        $repo->gitService = $host->id;
        $repo->project    = $MR->targetProject;
        $repo->password   = $host->token;
        $repo->account    = '';
        $repo->encoding   = $encoding;

        $lines      = array();
        $commitList = array();
        if($scm == 'gitlab')
        {
            if($MR->synced) $diffVersions = $this->apiGetDiffVersions($MR->hostID, $MR->targetProject, $MR->mriid);
            foreach($diffVersions as $diffVersion)
            {
                $singleDiff = $this->apiGetSingleDiffVersion($MR->hostID, $MR->targetProject, $MR->mriid, $diffVersion->id);
                if($singleDiff->state == 'empty') continue;

                $commits = $singleDiff->commits;
                $diffs   = $singleDiff->diffs;
                foreach($diffs as $index => $diff)
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
        $diff = $scm->engine->parseDiff($lines);
        return $diff;
    }

    /**
     * Get sudo account pair, such as "zentao account" => "gitlab account|id".
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $account
     * @access public
     * @return array
     */
    public function getSudoAccountPair($hostID, $projectID, $account)
    {
        $bindedUsers = $this->gitlab->getUserAccountIdPairs($hostID);
        $accountPair = array();
        if(isset($bindedUsers[$account])) $accountPair[$account] = $bindedUsers[$account];
        return $accountPair;
    }

    /**
     * Get sudo user ID in both GitLab and Project.
     * Note: sudo parameter in GitLab API can be user ID or username.
     * @param  int    $hostID
     * @param  int    $projectID
     * @access public
     * @return int|string
     */
    public function getSudoUsername($hostID, $projectID)
    {
        $zentaoUser = $this->app->user->account;

        /* Fetch user list both in Zentao and current GitLab project. */
        $bindedUsers     = $this->gitlab->getUserAccountIdPairs($hostID);
        $rawProjectUsers = $this->gitlab->apiGetProjectUsers($hostID, $projectID);
        $users           = array();
        foreach($rawProjectUsers as $rawProjectUser)
        {
            if(!empty($bindedUsers[$rawProjectUser->username])) $users[$rawProjectUser->username] = $bindedUsers[$rawProjectUser->username];
        }
        if(!empty($users[$zentaoUser])) return $users[$zentaoUser];
        return '';
    }

    /**
     * Create a todo item for merge request.
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiCreateMRTodo($hostID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/todo");
        return json_decode(commonModel::http($url, $data = null, $options = array(CURLOPT_CUSTOMREQUEST => 'POST')));
    }

    /**
     * Get diff versions of MR from GitLab API.
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetDiffVersions($hostID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/versions");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get a single diff version of MR from GitLab API.
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @param  int    $versionID
     * @access public
     * @return object
     */
    public function apiGetSingleDiffVersion($hostID, $projectID, $MRID, $versionID)
    {
        $url = sprintf($this->gitlab->getApiRoot($hostID), "/projects/$projectID/merge_requests/$MRID/versions/$versionID");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get diff of MR from Gitea API.
     *
     * @param  int    $hostID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetDiffs($hostID, $projectID, $MRID)
    {
        $url = sprintf($this->loadModel('gitea')->getApiRoot($hostID), "/repos/$projectID/pulls/$MRID.diff");
        return commonModel::http($url);
    }

    /**
     * Reject or Approve this MR.
     *
     * @param  object $MR
     * @param  string $action
     * @param  string $comment
     * @return array
     */
    public function approve($MR, $action = 'approve', $comment = '')
    {
        $this->loadModel('action');
        $actionID = $this->action->create('mr', $MR->id, $action);

        $oldMR = $MR;
        if(isset($MR->status) and $MR->status == 'opened')
        {
            $rawApprovalStatus = '';
            if(isset($MR->approvalStatus)) $rawApprovalStatus = $MR->approvalStatus;
            $MR->approver = $this->app->user->account;
            if($action == 'reject' and $rawApprovalStatus != 'rejected')  $MR->approvalStatus = 'rejected';
            if($action == 'approve' and $rawApprovalStatus != 'approved') $MR->approvalStatus = 'approved';
            if(isset($MR->approvalStatus) and $rawApprovalStatus != $MR->approvalStatus)
            {
                $changes = common::createChanges($oldMR, $MR);
                $this->action->logHistory($actionID, $changes);
                $this->dao->update(TABLE_MR)->data($MR)
                    ->where('id')->eq($MR->id)
                    ->exec();
                if (dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

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
                if (dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

                return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true);
            }
        }
        return array('result' => 'fail', 'message' => $this->lang->mr->repeatedOperation, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
    }

    /**
     * Close this MR.
     *
     * @param  mixed $MR
     * @return void
     */
    public function close($MR)
    {
        $this->loadModel('action');
        $actionID = $this->action->create('mr', $MR->id, 'closed');
        $rawMR = $this->apiCloseMR($MR->hostID, $MR->targetProject, $MR->mriid);
        $changes = common::createChanges($MR, $rawMR);
        $this->action->logHistory($actionID, $changes);
        if(isset($rawMR->state) and $rawMR->state == 'closed') return array('result' => 'success', 'message' => $this->lang->mr->closeSuccess, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
        return array('result' => 'fail', 'message' => $this->lang->fail, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
    }

    /**
     * Reopen this MR.
     *
     * @param  mixed $MR
     * @return void
     */
    public function reopen($MR)
    {
        $this->loadModel('action');
        $actionID = $this->action->create('mr', $MR->id, 'reopen');
        $rawMR = $this->apiReopenMR($MR->hostID, $MR->targetProject, $MR->mriid);
        $changes = common::createChanges($MR, $rawMR);
        $this->action->logHistory($actionID, $changes);
        if(isset($rawMR->state) and $rawMR->state == 'opened') return array('result' => 'success', 'message' => $this->lang->mr->reopenSuccess, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
        return array('result' => 'fail', 'message' => $this->lang->fail, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
    }


    /**
     * Get review.
     *
     * @param  int    $repoID
     * @param  int    $MRID
     * @param  string $revision
     * @access public
     * @return array
     */
    public function getReview($repoID, $MRID, $revision = '')
    {
        if(empty($repoID) or empty($MRID)) return array();

        $reviews = array();
        $bugs    = $this->dao->select('t1.*, t2.realname')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
            ->where('t1.repo')->eq((int)$repoID)
            ->andWhere('t1.mr')->eq((int)$MRID)
            ->beginIF($revision)->andWhere('t1.v2')->eq($revision)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');
        foreach($bugs as $bug)
        {
            if(common::hasPriv('bug', 'edit'))   $bug->edit   = true;
            if(common::hasPriv('bug', 'delete')) $bug->delete = true;
            if(common::hasPriv('bug', 'view'))   $bug->view   = true;
            $lines = explode(',', trim($bug->lines, ','));
            $line  = $lines[0];
            $reviews[$line]['bug'][$bug->id] = $bug;
        }

        $tasks = $this->dao->select('t1.*, t2.realname')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')
            ->where('t1.repo')->eq((int)$repoID)
            ->andWhere('t1.mr')->eq((int)$MRID)
            ->beginIF($revision)->andWhere('t1.v2')->eq($revision)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');
        foreach($tasks as $task)
        {
            if(common::hasPriv('task', 'edit'))   $task->edit   = true;
            if(common::hasPriv('task', 'delete')) $task->delete = true;
            if(common::hasPriv('task', 'view'))   $task->view   = true;
            $lines = explode(',', trim($task->lines, ','));
            $line  = $lines[0];
            $reviews[$line]['task'][$task->id] = $task;
        }

        return $reviews;
    }

    /**
     * Get bugs by repo.
     *
     * @param  int    $repoID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBugsByRepo($repoID, $browseType, $orderBy, $pager)
    {
        /* Get execution that user can access. */
        $executions = $this->loadModel('execution')->getPairs($this->session->project, 'all', 'empty|withdelete');

        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('repo')->eq($repoID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($this->app->user->view->products)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in(array_keys($executions))->fi()
            ->beginIF($browseType == 'assigntome')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'openedbyme')->andWhere('openedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'resolvedbyme')->andWhere('resolvedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assigntonull')->andWhere('assignedTo')->eq('')->fi()
            ->beginIF($browseType == 'unresolved')->andWhere('resolvedBy')->eq('')->fi()
            ->beginIF($browseType == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        return $bugs;
    }

    /**
     * Get execution pairs.
     *
     * @param  int    $product
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getExecutionPairs($product, $branch = 0)
    {
        $pairs = array();
        $executions = $this->loadModel('execution')->getList(0, 'all', 'undone', 0, $product, $branch);
        foreach($executions as $execution) $pairs[$execution->id] = $execution->name;
        return $pairs;
    }

    /**
     * Save bug.
     *
     * @param  int    $repoID
     * @param  int    $mr
     * @param  int    $v1
     * @param  int    $v2
     * @access public
     * @return array
     */
    public function saveBug($repoID, $mr, $v1, $v2)
    {
        $now  = helper::now();
        $data = fixer::input('post')
            ->stripTags('commentText', $this->config->allowedTags)
            ->add('pri', 2)
            ->add('severity', 2)
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('openedBuild', 'trunk')
            ->add('type', 'codeerror')
            ->add('repo', $repoID)
            ->add('mr', $mr)
            ->add('lines', $this->post->begin . ',' . $this->post->end)
            ->add('v1', $v1)
            ->add('v2', $v2)
            ->cleanInt('module,execution,mr,repo')
            ->remove('begin,end,uid,reviewType,taskExecution,taskModule,taskAssignedTo')
            ->get();

        $data->steps = $this->loadModel('file')->pasteImage($data->commentText, $this->post->uid);
        if($data->execution) $data->project = (int)$this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($data->execution)->fetch('project');
        if($data->assignedTo) $data->assignedDate = $now;
        unset($data->commentText);

        $this->loadModel('bug');
        foreach(explode(',', $this->config->bug->create->requiredFields . ',repo,mr') as $requiredField)
        {
            $requiredField = trim($requiredField);
            if(empty($requiredField)) continue;
            if(!isset($data->$requiredField)) continue;
            if(empty($data->$requiredField))
            {
                $fieldName = $requiredField;
                if(isset($this->lang->bug->$requiredField)) $fieldName = $this->lang->bug->$requiredField;
                dao::$errors[$requiredField][] = sprintf($this->lang->error->notempty, $fieldName);
            }
        }
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $this->dao->insert(TABLE_BUG)->data($data)->autocheck()->exec();

        if(!dao::isError())
        {
            $bugID = $this->dao->lastInsertID();
            $this->loadModel('file')->updateObjectID($this->post->uid, $bugID, 'bug');
            helper::setcookie("repoPairs[$repoID]", $data->product);

            $bugInfo = array();
            $bugInfo['result']     = 'success';
            $bugInfo['id']         = $bugID;
            $bugInfo['realname']   = $this->app->user->realname;
            $bugInfo['openedDate'] = substr($now, 5, 11);
            $bugInfo['edit']       = common::hasPriv('bug', 'edit');
            $bugInfo['view']       = common::hasPriv('bug', 'view');
            $bugInfo['delete']     = common::hasPriv('bug', 'delete');
            $bugInfo['lines']      = $data->lines;
            $bugInfo['line']       = $this->post->begin;
            $bugInfo['content']    = $data->steps;
            $bugInfo['title']      = $data->title;
            $bugInfo['objectType'] = 'bug';
            return $bugInfo;
        }

        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Save task.
     *
     * @param  int    $repoID
     * @param  int    $mr
     * @param  int    $v1
     * @param  int    $v2
     * @access public
     * @return array
     */
    public function saveTask($repoID, $mr, $v1, $v2)
    {
        $now  = helper::now();
        $data = fixer::input('post')->stripTags('commentText', $this->config->allowedTags)->get();

        $task = new stdclass();
        $task->execution  = (int)$data->taskExecution;
        $task->project    = (int)$this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch('project');
        $task->module     = (int)$data->taskModule;
        $task->name       = $data->title;
        $task->type       = 'devel';
        $task->pri        = '2';
        $task->status     = 'wait';
        $task->version    = '1';
        $task->openedBy   = $this->app->user->account;
        $task->assignedTo = $data->taskAssignedTo;
        $task->repo       = (int)$repoID;
        $task->mr         = (int)$mr;
        $task->lines      = $this->post->begin . ',' . $this->post->end;
        $task->entry      = helper::safe64Decode($data->entry);
        $task->v1         = $v1;
        $task->v2         = $v2;
        $task->desc       = $this->loadModel('file')->pasteImage($data->commentText, $this->post->uid);
        if($task->assignedTo) $task->assignedDate = $now;

        $this->loadModel('task');
        foreach(explode(',', $this->config->task->create->requiredFields . ',repo,mr') as $requiredField)
        {
            $requiredField = trim($requiredField);
            if(empty($requiredField)) continue;
            if(!isset($task->$requiredField)) continue;
            if(empty($task->$requiredField))
            {
                $fieldName = $requiredField;
                if(isset($this->lang->task->$requiredField)) $fieldName = $this->lang->task->$requiredField;
                dao::$errors[$requiredField][] = sprintf($this->lang->error->notempty, $fieldName);
            }
        }
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $this->dao->insert(TABLE_TASK)->data($task)->autocheck()->exec();

        if(!dao::isError())
        {
            $taskID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $taskID, 'task');

            $taskInfo = array();
            $taskInfo['result']     = 'success';
            $taskInfo['id']         = $taskID;
            $taskInfo['realname']   = $this->app->user->realname;
            $taskInfo['openedDate'] = substr($now, 5, 11);
            $taskInfo['edit']       = common::hasPriv('task', 'edit');
            $taskInfo['view']       = common::hasPriv('task', 'view');
            $taskInfo['delete']     = common::hasPriv('task', 'delete');
            $taskInfo['lines']      = $task->lines;
            $taskInfo['line']       = $this->post->begin;
            $taskInfo['content']    = $task->desc;
            $taskInfo['title']      = $data->title;
            $taskInfo['objectType'] = 'task';
            $taskInfo['entry']      = $task->entry;
            return $taskInfo;
        }

        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Update bug.
     *
     * @param  int    $bugID
     * @param  string $title
     * @access public
     * @return string
     */
    public function updateBug($bugID, $title)
    {
        $this->dao->update(TABLE_BUG)->set('title')->eq($title)->where('id')->eq($bugID)->exec();
        return $title;
    }

    /**
     * Update comment.
     *
     * @param  int    $commentID
     * @param  string $comment
     * @access public
     * @return string
     */
    public function updateComment($commentID, $comment)
    {
        $this->dao->update(TABLE_ACTION)->set('comment')->eq($comment)->where('id')->eq($commentID)->exec();
        return $comment;
    }

    /**
     * Delete comment.
     *
     * @param  int    $commentID
     * @access public
     * @return void
     */
    public function deleteComment($commentID)
    {
        return $this->dao->delete()->from(TABLE_ACTION)->where('id')->eq($commentID)->exec();
    }

    /**
     * Get last review info.
     *
     * @param  int    $repoID
     * @access public
     * @return object
     */
    public function getLastReviewInfo($repoID)
    {
        if(empty($repoID)) return null;

        $lastReview = new stdclass();
        $lastReview->bug  = $this->dao->select('*')->from(TABLE_BUG)->where('repo')->eq((int)$repoID)->orderby('id_desc')->fetch();
        $lastReview->task = $this->dao->select('*')->from(TABLE_TASK)->where('repo')->eq((int)$repoID)->orderby('id_desc')->fetch();
        return $lastReview;
    }

    /**
     * Get mr link list.
     *
     * @param int    $MRID
     * @param int    $productID
     * @param string $type
     * @param string $orderBy
     * @param object $pager
     * @access public
     * @return array
     */
    public function getLinkList($MRID, $productID, $type, $orderBy = 'id_desc', $pager = null)
    {
        $linkIDs = $this->dao->select('BID')->from(TABLE_RELATION)
            ->where('product')->eq($productID)
            ->andWhere('relation')->eq('interrated')
            ->andWhere('AType')->eq('mr')
            ->andWhere('AID')->eq($MRID)
            ->andWhere('BType')->eq($type)
            ->fetchPairs('BID');

        $links = array();
        if($type == 'story' and !empty($linkIDs))
        {
            $orderBy = str_replace('name_', 'title_', $orderBy);
            $links = $this->dao->select('t1.*, t2.spec, t2.verify, t3.name as productTitle')
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
        if($type == 'bug' and !empty($linkIDs))
        {
            $orderBy = str_replace('name_', 'title_', $orderBy);
            $links = $this->dao->select('*')->from(TABLE_BUG)
                ->where('deleted')->eq(0)
                ->andWhere('id')->in($linkIDs)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }
        if($type == 'task' and !empty($linkIDs))
        {
            $orderBy = str_replace('title_', 'name_', $orderBy);
            $links = $this->dao->select('*')->from(TABLE_TASK)
                ->where('deleted')->eq(0)
                ->andWhere('id')->in($linkIDs)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return $links;
    }

    /**
     * Get linked MR pairs.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getLinkedMRPairs($objectID, $objectType = 'story')
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
     * Get story,task,bug pairs which linked MR.
     *
     * @param  int    $MRID
     * @param  string $objectType story|task|bug
     * @access public
     * @return array
     */
    public function getLinkedObjectPairs($MRID, $objectType = 'story')
    {
        $table = $this->config->objectTables[$objectType];
        return $this->dao->select('relation.BID')->from(TABLE_RELATION)->alias('relation')
            ->leftJoin($table)->alias('object')->on('relation.BID = object.id')
            ->where('relation.AType')->eq('mr')
            ->andWhere('relation.BType')->eq($objectType)
            ->andWhere('relation.AID')->eq($MRID)
            ->andWhere('object.deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Get diff commits of MR.
     *
     * @param  object $MR
     * @access public
     * @return array
     */
    public function getDiffCommits($MR)
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
            $projectID = $MR->targetProject;
            $MRID      = $MR->mriid;
            if($host->type == 'gitlab') $url = sprintf($this->loadModel('gitlab')->getApiRoot($MR->hostID), "/projects/$projectID/merge_requests/$MRID/commits");
            if($host->type == 'gitea')  $url = sprintf($this->loadModel('gitea')->getApiRoot($MR->hostID), "/repos/$projectID/pulls/$MRID/commits");
            return json_decode(commonModel::http($url));
        }
    }

    /**
     * Create an mr link.
     *
     * @param int    $MRID
     * @param int    $productID
     * @param string $type
     * @access public
     * @return void
     */
    public function link($MRID, $productID, $type)
    {
        $this->loadModel('action');
        if($type == 'story') $links = $this->post->stories;
        if($type == 'bug')   $links = $this->post->bugs;
        if($type == 'task')  $links = $this->post->tasks;

        /* Get link action text. */
        $MR             = $this->getByID($MRID);
        $users          = $this->loadModel('user')->getPairs('noletter');
        $MRCreateAction = $MR->createdDate . '::' . zget($users, $MR->createdBy) . '::' . helper::createLink('mr', 'view', "mr={$MR->id}");

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

            if($type == 'story') $this->action->create('story', $linkID, 'createmr', '', $MRCreateAction);
            if($type == 'bug')   $this->action->create('bug', $linkID, 'createmr', '', $MRCreateAction);
            if($type == 'task')  $this->action->create('task', $linkID, 'createmr', '', $MRCreateAction);
        }
    }

    /**
     * Link objects.
     *
     * @param  object $MR
     * @access public
     * @return void
     */
    public function linkObjects($MR)
    {
        $this->loadModel('repo');
        $this->loadModel('action');

        /* Init objects. */
        $stories = $bugs = $tasks = array();

        /* Get commits by MR. */
        $commits = $this->apiGetMRCommits($MR->hostID, $MR->targetProject, $MR->mriid);
        if(empty($commits)) return true;

        foreach($commits as $commit)
        {
            $objects = $this->repo->parseComment($commit->message);
            $stories = array_merge($stories, $objects['stories']);
            $bugs    = array_merge($bugs,    $objects['bugs']);
            $tasks   = array_merge($tasks,   $objects['tasks']);
        }

        $users          = $this->loadModel('user')->getPairs('noletter');
        $MRCreateAction = $MR->createdDate . '::' . zget($users, $MR->createdBy) . '::' . helper::createLink('mr', 'view', "mr={$MR->id}");
        $product        = $this->getMRProduct($MR);

        foreach($stories as $storyID)
        {
            $relation           = new stdclass;
            $relation->product  = $product->id;
            $relation->AType    = 'mr';
            $relation->AID      = $MR->id;
            $relation->relation = 'interrated';
            $relation->BType    = 'story';
            $relation->BID      = $storyID;

            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            $this->action->create('story', $storyID, 'createmr', '', $MRCreateAction);
        }

        foreach($bugs as $bugID)
        {
            $relation           = new stdclass;
            $relation->product  = $product->id;
            $relation->AType    = 'mr';
            $relation->AID      = $MR->id;
            $relation->relation = 'interrated';
            $relation->BType    = 'bug';
            $relation->BID      = $bugID;

            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            $this->action->create('bug', $bugID, 'createmr', '', $MRCreateAction);
        }

        foreach($tasks as $taskID)
        {
            $relation           = new stdclass;
            $relation->product  = $product->id;
            $relation->AType    = 'mr';
            $relation->AID      = $MR->id;
            $relation->relation = 'interrated';
            $relation->BType    = 'task';
            $relation->BID      = $taskID;

            $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            $this->action->create('task', $taskID, 'createmr', '', $MRCreateAction);
        }
    }

    /**
     * unLink an mr link.
     *
     * @param int    $MRID
     * @param int    $productID
     * @param string $type
     * @param int    $linkID
     * @access public
     * @return void
     */
    public function unlink($MRID, $productID, $type, $linkID)
    {
        $this->dao->delete()->from(TABLE_RELATION)->where('product')->eq($productID)->andWhere('AType')->eq('mr')->andWhere('AID')->eq($MRID)->andWhere('BType')->eq($type)->andWhere('BID')->eq($linkID)->exec();

        $this->loadModel('action')->create($type, $linkID, 'deletemr', '', helper::createLink('mr', 'view', "mr={$MRID}"));
    }

    /**
     * Get links by mr commites.
     *
     * @param  object $MR
     * @param  string $type
     * @access public
     * @return array
     */
    public function getCommitedLink($MR, $type)
    {
        $diffCommits = $this->getDiffCommits($MR);

        $commits = array();
        foreach($diffCommits as $diffCommit)
        {
            if(isset($diffCommit->id)) $commits[] = substr($diffCommit->id, 0, 10);
        }

        return $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq($type)->andWhere('extra')->in($commits)->fetchPairs('objectID');
    }

    /**
     * Get mr product.
     *
     * @param object $MR
     * @access public
     * @return mix
     */
    public function getMRProduct($MR)
    {
        $product = array();

        if(is_object($MR) && $MR->repoID)
        {
            $productID = $this->dao->select('product')->from(TABLE_REPO)->where('id')->eq($MR->repoID)->fetch('product');
        }
        else
        {
            $products  = $this->loadModel('gitlab')->getProductsByProjects(array($MR->targetProject, $MR->sourceProject));
            $productID = array_shift($products);
        }

        if($productID) $product = $this->loadModel('product')->getById($productID);
        return $product;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object $MR
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($MR)
    {
        return array($MR->createdBy, $MR->assignee);
    }

    /**
     * Log merged action to links.
     *
     * @param object $MR
     * @access public
     * @return void
     */
    public function logMergedAction($MR)
    {
        $this->loadModel('action')->create('mr', $MR->id, 'mergedmr');
        $product = $this->getMRProduct($MR);

        $stories = $this->getLinkList($MR->id, $product->id, 'story');
        foreach($stories as $story)
        {
            $this->action->create('story', $story->id, 'mergedmr', '', helper::createLink('mr', 'view', "mr={$MR->id}"));
        }

        $bugs = $this->getLinkList($MR->id, $product->id, 'bug');
        foreach($bugs as $bug)
        {
            $this->action->create('bug', $bug->id, 'mergedmr', '', helper::createLink('mr', 'view', "mr={$MR->id}"));
        }

        $tasks = $this->getLinkList($MR->id, $product->id, 'task');
        foreach($tasks as $task)
        {
            $this->action->create('task', $task->id, 'mergedmr', '', helper::createLink('mr', 'view', "mr={$MR->id}"));
        }

        return $this->dao->update(TABLE_MR)->data(array('status' => 'merged'))->where('id')->eq($MR->id)->exec();
    }

    /**
     * Check same opened mr for source branch.
     *
     * @param  int    $hostID
     * @param  int    $sourceProject
     * @param  string $sourceBranch
     * @param  int    $targetProject
     * @param  string $targetBranch
     * @access public
     * @return array
     */
    public function checkSameOpened($hostID, $sourceProject, $sourceBranch, $targetProject, $targetBranch)
    {
        if(empty($sourceProject) or empty($sourceBranch) or empty($targetProject) or empty($targetBranch)) return array('result' => 'success');

        if($sourceProject == $targetProject and $sourceBranch == $targetBranch) return array('result' => 'fail', 'message' => $this->lang->mr->errorLang[1]);
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

        $MR = $this->apiGetSameOpened($hostID, $sourceProject, $sourceBranch, $targetProject, $targetBranch);
        if($MR) return array('result' => 'fail', 'message' => sprintf($this->lang->mr->errorLang[2], $MR->iid));
        return array('result' => 'success');
    }

    /**
     * Convert API error.
     *
     * @param  array  $message
     * @access public
     * @return string
     */
    public function convertApiError($message)
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
}

<?php
/**
 * The model file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * @param  string   $mode
     * @param  string   $param
     * @param  string   $orderBy
     * @param  object   $pager
     * @access public
     * @return array
     */
    public function getList($mode = 'all', $param = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $MRList = $this->dao->select('*')
            ->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->beginIF($mode == 'status' and $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($mode == 'assignee' and $param != 'all')->andWhere('assignee')->eq($param)->fi()
            ->beginIF($mode == 'creator' and $param != 'all')->andWhere('createdBy')->eq($param)->fi()
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
        return array('' => '') + $MR;
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
            ->setDefault('needCI', 0)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

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

        $this->dao->insert(TABLE_MR)->data($MR, $this->config->mr->create->skippedFields)
            ->batchCheck($this->config->mr->create->requiredFields, 'notempty')
            ->checkIF($MR->needCI, 'jobID',  'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $MRID = $this->dao->lastInsertId();

        $MRObject = new stdclass;
        $MRObject->target_project_id = $MR->targetProject;
        $MRObject->source_branch     = $MR->sourceBranch;
        $MRObject->target_branch     = $MR->targetBranch;
        $MRObject->title             = $MR->title;
        $MRObject->description       = $MR->description;
        if($MR->assignee)
        {
            $gitlabAssignee = $this->gitlab->getUserIDByZentaoAccount($this->post->gitlabID, $MR->assignee);
            if($gitlabAssignee) $MRObject->assignee_ids = $gitlabAssignee;
        }

        $rawMR = $this->apiCreateMR($this->post->gitlabID, $this->post->sourceProject, $MRObject);

        /**
         * Another open merge request already exists for this source branch.
         * The type of variable `$rawMR->message` is array.
         */
        if(isset($rawMR->message) and !isset($rawMR->iid))
        {
            $this->dao->delete()->from(TABLE_MR)->where('id')->eq($MRID)->exec();
            return array('result' => 'fail', 'message' => sprintf($this->lang->mr->apiError->createMR, $rawMR->message[0]));
        }

        /* Create MR failed. */
        if(!isset($rawMR->iid))
        {
            $this->dao->delete()->from(TABLE_MR)->where('id')->eq($MRID)->exec();
            return array('result' => 'fail', 'message' => $this->lang->mr->createFailedFromAPI);
        }

        /* Create a todo item for this MR. */
        if(empty($MR->jobID)) $this->apiCreateMRTodo($this->post->gitlabID, $this->post->targetProject, $rawMR->iid);

        $newMR = new stdclass;
        $newMR->mriid       = $rawMR->iid;
        $newMR->status      = $rawMR->state;
        $newMR->mergeStatus = $rawMR->merge_status;

        /* Update MR in Zentao database. */
        $this->dao->update(TABLE_MR)->data($newMR)
            ->where('id')->eq($MRID)
            ->autoCheck()
            ->exec();
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => helper::createLink('mr', 'browse'));
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
            ->setDefault('repoID', 0)
            ->setDefault('needCI', 0)
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', helper::now())
            ->get();
        $oldMR = $this->getByID($MRID);

        $this->dao->update(TABLE_MR)->data($MR)->checkIF($MR->needCI, 'jobID',  'notempty');
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        /* Exec Job */
        if(isset($MR->jobID) && $MR->jobID)
        {
            $pipeline = $this->loadModel('job')->exec($MR->jobID);

            if(!empty($pipeline->queue) && $MR->jobID != $oldMR->jobID)
            {
                $compile = $this->loadModel('compile')->getByQueue($pipeline->queue);
                $MR->compileID = $compile->id;
                $MR->compileStatus = $compile->status;
            }
        }

        /* Update MR in GitLab. */
        $newMR = new stdclass;
        $newMR->title         = $MR->title;
        $newMR->description   = $MR->description;
        $newMR->target_branch = $MR->targetBranch;
        if($MR->assignee)
        {
            $gitlabAssignee = $this->gitlab->getUserIDByZentaoAccount($oldMR->gitlabID, $MR->assignee);
            if($gitlabAssignee) $newMR->assignee_ids = $gitlabAssignee;
        }

        /* Known issue: `reviewer_ids` takes no effect. */
        $rawMR = $this->apiUpdateMR($oldMR->gitlabID, $oldMR->targetProject, $oldMR->mriid, $newMR);

        /* Update MR in Zentao database. */
        $this->dao->update(TABLE_MR)->data($MR, $this->config->mr->edit->skippedFields)
            ->where('id')->eq($MRID)
            ->batchCheck($this->config->mr->edit->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();
        $MR = $this->getByID($MRID);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => helper::createLink('mr', 'browse'));
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
        $rawMR = $this->apiGetSingleMR($MR->gitlabID, $MR->targetProject, $MR->mriid);
        /* Sync MR in ZenTao database whatever status of MR in GitLab. */
        if(isset($rawMR->iid))
        {
            $map         = $this->config->mr->maps->sync;
            $gitlabUsers = $this->gitlab->getUserIdAccountPairs($MR->gitlabID);

            $newMR = new stdclass;
            foreach($map as $syncField => $config)
            {
                $value = '';
                list($field, $optionType, $options) = explode('|', $config);

                if($optionType == 'field')       $value = $rawMR->$field;
                if($optionType == 'userPairs')
                {
                    $gitlabUserID = '';
                    if(isset($rawMR->$field[0]))
                    {
                        $gitlabUserID = $rawMR->$field[0]->$options;
                    }
                    $value = zget($gitlabUsers, $gitlabUserID, '');
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
     * @access public
     * @return void
     */
    public function batchSyncMR($MRList)
    {
        if(!empty($MRList)) foreach($MRList as $key => $MR)
        {
            if($MR->status != 'opened') continue;
            $rawMR = $this->apiGetSingleMR($MR->gitlabID, $MR->targetProject, $MR->mriid);

            if(isset($rawMR->iid))
            {
                /* create gitlab mr todo to zentao todo */
                $this->batchSyncTodo($MR->gitlabID, $MR->targetProject);

                $map         = $this->config->mr->maps->sync;
                $gitlabUsers = $this->gitlab->getUserIdAccountPairs($MR->gitlabID);

                $newMR = new stdclass;

                foreach($map as $syncField => $config)
                {
                    $value = '';
                    list($field, $optionType, $options) = explode('|', $config);

                    if($optionType == 'field') $value = $rawMR->$field;
                    if($optionType == 'userPairs')
                    {
                        $gitlabUserID = '';
                        if(isset($rawMR->$field[0]))
                        {
                            $gitlabUserID = $rawMR->$field[0]->$options;
                        }
                        $value = zget($gitlabUsers, $gitlabUserID, '');
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
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchSyncTodo($gitlabID, $projectID)
    {
        /* It can only get todo from GitLab API by its assignee. So here should use sudo as the assignee to get the todo list. */
        /* In this case, ignore sync todo for reviewer due to an issue in GitLab API. */
        $accountList = $this->dao->select('assignee')->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->andWhere('status')->eq('opened')
            ->andWhere('gitlabID')->eq($gitlabID)
            ->andWhere('targetProject')->eq($projectID)
            ->fetchPairs();

        foreach($accountList as $account)
        {
            $accountPair = $this->getSudoAccountPair($gitlabID, $projectID, $account);
            if(!empty($accountPair) and isset($accountPair[$account]))
            {
                $sudo  = $accountPair[$account];
                $todoList = $this->gitlab->apiGetTodoList($gitlabID, $projectID, $sudo);

                foreach($todoList as $rawTodo)
                {
                    $todoDesc = $this->dao->select('*')
                        ->from(TABLE_TODO)
                        ->where('idvalue')->eq($rawTodo->id)
                        ->fetch();
                    if(empty($todoDesc))
                    {
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
                        $todo->desc         = $rawTodo->target->assignee->name . '&nbsp;' . $this->lang->mr->at . '&nbsp;' . '<a href="' . $this->gitlab->apiGetSingleProject($gitlabID, $projectID)->web_url . '" target="_blank">' . $rawTodo->project->path .'</a>' . '&nbsp;' . $this->lang->mr->todomessage . '<a href="' . $rawTodo->target->web_url . '" target="_blank">' . '&nbsp;' . $this->lang->mr->common .'</a>' . '。';
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
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function todoDescriptionLink($gitlabID, $projectID)
    {
        $gitlab = $this->gitlab->getByID($gitlabID);
        if(!$gitlab) return '';
        return rtrim($gitlab->url, '/')."/dashboard/todos?project_id=$projectID&type=MergeRequest";
    }

    /**
     * Create MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#create-mr
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiCreateMR($gitlabID, $projectID, $MR)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests");
        return json_decode(commonModel::http($url, $MR));
    }

    /**
     * Get MR list by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#list-project-merge-requests
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function apiGetMRList($gitlabID, $projectID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get single MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#get-single-mr
     * @param  int    $gitlabID
     * @param  int    $projectID  targetProject
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetSingleMR($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID");
        return json_decode(commonModel::http($url));
    }

    /**
     * Update MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @param  object $MR
     * @access public
     * @return object
     */
    public function apiUpdateMR($gitlabID, $projectID, $MRID, $MR)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID");
        return json_decode(commonModel::http($url, $MR, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * Delete MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#delete-a-merge-request
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiDeleteMR($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID");
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE')));
    }

     /**
     * Close MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiCloseMR($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID") . '&state_event=close';
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * Reopen MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiReopenMR($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID") . '&state_event=reopen';
        return json_decode(commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * Accept MR by API.
     *
     * @link   https://docs.gitlab.com/ee/api/merge_requests.html#accept-mr
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @param  string $sudo
     * @access public
     * @return object
     */
    public function apiAcceptMR($gitlabID, $projectID, $MRID, $sudo = "")
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID/merge");
        if($sudo != "") return json_decode(commonModel::http($url, $data = null, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT'), $headers = array("sudo: {$sudo}")));
        return json_decode(commonModel::http($url, $data = null, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
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
        $diffVersions = $this->apiGetDiffVersions($MR->gitlabID, $MR->targetProject, $MR->mriid);
        $gitlab = $this->gitlab->getByID($MR->gitlabID);

        $this->loadModel('repo');
        $repo = new stdclass;
        $repo->SCM      = 'GitLab';
        $repo->gitlab   = $gitlab->id;
        $repo->project  = $MR->targetProject;
        $repo->path     = sprintf($this->config->repo->gitlab->apiPath, $gitlab->url, $MR->targetProject);
        $repo->client   = $gitlab->url;
        $repo->password = $gitlab->token;
        $repo->account  = '';
        $repo->encoding = $encoding;

        $lines      = array();
        $commitList = array();
        foreach ($diffVersions as $diffVersion)
        {
            $singleDiff = $this->apiGetSingleDiffVersion($MR->gitlabID, $MR->targetProject, $MR->mriid, $diffVersion->id);
            if ($singleDiff->state == 'empty') continue;
            $commits = $singleDiff->commits;
            $diffs   = $singleDiff->diffs;
            foreach ($diffs as $index => $diff)
            {
                if(empty($commits[$index])) continue;
                /* Make sure every file with same commitID is unique in $lines. */
                $shortID = $commits[$index]->short_id;
                if(in_array($shortID, $commitList)) continue;
                $commitList[] = $shortID;

                $lines[] = sprintf("diff --git a/%s b/%s", $diff->old_path, $diff->new_path);
                $lines[] = sprintf("index %s ... %s %s ", $singleDiff->head_commit_sha, $singleDiff->base_commit_sha, $diff->b_mode);
                $lines[] = sprintf("--a/%s", $diff->old_path);
                $lines[] = sprintf("--b/%s", $diff->new_path);
                $diffLines = explode("\n", $diff->diff);
                foreach ($diffLines as $diffLine) $lines[] = $diffLine;
            }
        }
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $diff = $scm->engine->parseDiff($lines);
        return $diff;
    }

    /**
     * Get sudo account pair, such as "zentao account" => "gitlab account|id".
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $account
     * @access public
     * @return array
     */
    public function getSudoAccountPair($gitlabID, $projectID, $account)
    {
        $bindedUsers = $this->gitlab->getUserAccountIdPairs($gitlabID);
        $accountPair = array();
        if(isset($bindedUsers[$account])) $accountPair[$account] = $bindedUsers[$account];
        return $accountPair;
    }

    /**
     * Get sudo user ID in both GitLab and Project.
     * Note: sudo parameter in GitLab API can be user ID or username.
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return int|string
     */
    public function getSudoUsername($gitlabID, $projectID)
    {
        $zentaoUser = $this->app->user->account;

        /* Fetch user list both in Zentao and current GitLab project. */
        $bindedUsers     = $this->gitlab->getUserAccountIdPairs($gitlabID);
        $rawProjectUsers = $this->gitlab->apiGetProjectUsers($gitlabID, $projectID);
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
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiCreateMRTodo($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID/todo");
        return json_decode(commonModel::http($url, $data = null, $options = array(CURLOPT_CUSTOMREQUEST => 'POST')));
    }

    /**
     * Get diff versions of MR from GitLab API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetDiffVersions($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID/versions");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get a single diff version of MR from GitLab API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @param  int    $versionID
     * @access public
     * @return object
     */
    public function apiGetSingleDiffVersion($gitlabID, $projectID, $MRID, $versionID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID/versions/$versionID");
        return json_decode(commonModel::http($url));
    }

    /**
     * Get diff commits of MR from GitLab API.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiGetDiffCommits($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID/commits");
        return json_decode(commonModel::http($url));
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
        $actionID = $this->action->create('mrapproval', $MR->id, $action);

        $oldMR = $MR;
        if(isset($MR->status) and $MR->status == 'opened')
        {
            $rawApprovalStatus = '';
            if(isset($MR->approvalStatus)) $rawApprovalStatus = $MR->approvalStatus;
            $MR->approver = $this->app->user->account;
            if ($action == 'reject' and $rawApprovalStatus != 'rejected') $MR->approvalStatus = 'rejected';
            if ($action == 'approve' and $rawApprovalStatus != 'approved') $MR->approvalStatus = 'approved';
            if (isset($MR->approvalStatus) and $rawApprovalStatus != $MR->approvalStatus)
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

                return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'parent.refresh()');
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
        $actionID = $this->action->create('mr', $MR->id, 'close');
        $rawMR = $this->apiCloseMR($MR->gitlabID, $MR->targetProject, $MR->mriid);
        $changes = common::createChanges($MR, $rawMR);
        $this->action->logHistory($actionID, $changes);
        if(isset($rawMR->state) and $rawMR->state == 'closed') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
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
        $rawMR = $this->apiReopenMR($MR->gitlabID, $MR->targetProject, $MR->mriid);
        $changes = common::createChanges($MR, $rawMR);
        $this->action->logHistory($actionID, $changes);
        if(isset($rawMR->state) and $rawMR->state == 'opened') return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
        return array('result' => 'fail', 'message' => $this->lang->fail, 'locate' => helper::createLink('mr', 'view', "mr={$MR->id}"));
    }


    /**
     * Get review.
     *
     * @param  int    $repoID
     * @param  string $entry
     * @param  string $revision
     * @access public
     * @return array
     */
    public function getReview($repoID, $entry, $revision)
    {
        $reviews = array();
        $bugs    = $this->dao->select('t1.*, t2.realname')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.openedBy = t2.account')
            ->where('t1.repo')->eq($repoID)
            ->andWhere('t1.entry')->eq($entry)
            ->andWhere('t1.v2')->eq($revision)
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');
        $comments = $this->dao->select('t1.*, t2.realname')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.actor = t2.account')
            ->where('t1.objectType')->eq('bug')
            ->andWhere('t1.objectID')->in(array_keys($bugs))
            ->andWhere('t1.action')->eq('commented')
            ->fetchGroup('objectID', 'id');
        foreach($bugs as $bug)
        {
            if(common::hasPriv('bug', 'edit'))   $bug->edit   = true;
            if(common::hasPriv('bug', 'delete')) $bug->delete = true;
            $lines = explode(',', trim($bug->lines, ','));
            $line  = $lines[0];
            $reviews[$line]['bugs'][$bug->id] = $bug;

            if(isset($comments[$bug->id]))
            {
                foreach($comments[$bug->id] as $key => $comment)
                {
                    if($comment->actor == $this->app->user->account) $comment->edit = true;
                }
                $reviews[$line]['comments'] = $comments;
            }
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
     * @param  string $file
     * @param  int    $v1
     * @param  int    $v2
     * @access public
     * @return array
     */
    public function saveBug($repoID, $file, $v1, $v2)
    {
        $now  = helper::now();
        $data = fixer::input('post')
            ->add('severity', 3)
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->add('openedBuild', 'trunk')
            ->add('assignedDate', $now)
            ->add('type', 'codeimprovement')
            ->add('repo', $repoID)
            ->add('entry', $file)
            ->add('lines', $this->post->begin . ',' . $this->post->end)
            ->add('v1', $v1)
            ->add('v2', $v2)
            ->remove('commentText,begin,end,uid')
            ->get();

        $data->steps = $this->loadModel('file')->pasteImage($this->post->commentText, $this->post->uid);
        $this->dao->insert(TABLE_BUG)->data($data)->exec();

        if(!dao::isError())
        {
            $bugID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $bugID, 'bug');
            setcookie("repoPairs[$repoID]", $data->product);

            return array('result' => 'success', 'id' => $bugID, 'realname' => $this->app->user->realname, 'openedDate' => substr($now, 5, 11), 'edit' => true, 'delete' => true, 'lines' => $data->lines, 'line' => $this->post->begin, 'steps' => $data->steps, 'title' => $data->title);
        }

        return array('result' => 'fail', 'message' => join("\n", dao::getError()));
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
     * @param  string $entry
     * @access public
     * @return object
     */
    public function getLastReviewInfo($entry)
    {
        return $this->dao->select('*')->from(TABLE_BUG)->where('entry')->eq($entry)->orderby('id_desc')->fetch();
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
        $MR       = $this->getByID($MRID);
        $users    = $this->loadModel('user')->getPairs('noletter');
        $MRCreateAction = sprintf($this->lang->mr->createAction, $MR->createdDate, zget($users, $MR->createdBy), helper::createLink('mr', 'view', "mr={$MR->id}"));

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
        return $this->dao->delete()->from(TABLE_RELATION)->where('product')->eq($productID)->andWhere('AType')->eq('mr')->andWhere('AID')->eq($MRID)->andWhere('BType')->eq($type)->andWhere('BID')->eq($linkID)->exec();
    }

    /**
     * Get links by mr commites.
     *
     * @param int    $gitlabID
     * @param int    $projectID
     * @param int    $MRID
     * @param string $type
     * @access public
     * @return array
     */
    public function getCommitedLink($gitlabID, $projectID, $MRID, $type)
    {
        $DiffCommits = $this->apiGetDiffCommits($gitlabID, $projectID, $MRID);

        $commits = array();
        foreach($DiffCommits as $DiffCommit)
        {
            $commits[] = substr($DiffCommit->id, 0, 10);
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

        if($MR->repoID)
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
     * @param  object $mr
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($mr)
    {
        return array($mr->createdBy, $mr->assignee);
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
        $this->loadModel('action');
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
    }
}

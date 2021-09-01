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
        return $this->dao->select('*')->from(TABLE_MR)->where('id')->eq($id)->fetch();
    }

    /**
     * Get MR list of gitlab project.
     *
     * @param  string   $orderBy
     * @param  object   $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        $MRList = $this->dao->select('*')
            ->from(TABLE_MR)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        foreach($MRList as $MR) $this->apiSyncMR($MR);

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
            ->AndWhere('repoID')->eq($repoID)
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
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        $this->dao->insert(TABLE_MR)->data($MR, $this->config->MR->create->skippedFields)
            ->batchCheck($this->config->MR->create->requiredFields, 'notempty')
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
        $MRObject->assignee_ids      = $MR->assignee;
        $MRObject->reviewer_ids      = $MR->reviewer;

        $rawMR = $this->apiCreateMR($this->post->gitlabID, $this->post->sourceProject, $MRObject);

        /* Another open merge request already exists for this source branch. */
        if(isset($rawMR->message) and !isset($rawMR->iid))
        {
            $this->dao->delete()->from(TABLE_MR)->where('id')->eq($MRID)->exec();
            return array('result' => 'fail', 'message' => sprintf($this->lang->mr->apiError->createMR, $rawMR->message));
        }

        /* Create MR failed. */
        if(!isset($rawMR->iid))
        {
            $this->dao->delete()->from(TABLE_MR)->where('id')->eq($MRID)->exec();
            return array('result' => 'fail', 'message' => $this->lang->mr->createFailedFromAPI);
        }

        $newMR = new stdclass;
        $newMR->mriid       = $rawMR->iid;
        $newMR->status      = $rawMR->state;
        $newMR->mergeStatus = $rawMR->merge_status;

        /* Change gitlab user ID to zentao account. */
        $gitlabUsers  = $this->gitlab->getUserIdAccountPairs($MR->gitlabID);
        $newMR->assignee = zget($gitlabUsers, $MR->assignee, '');
        $newMR->reviewer = zget($gitlabUsers, $MR->reviewer, '');

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
            ->setDefault('editedBy', $this->app->user->account)
            ->setDefault('editedDate', helper::now())
            ->get();

        /* Update MR in GitLab. */
        $newMR = new stdclass;
        $newMR->title        = $MR->title;
        $newMR->description  = $MR->description;
        $newMR->assignee     = $MR->assignee;
        $newMR->reviewer     = $MR->reviewer;
        $newMR->targetBranch = $MR->targetBranch;

        $oldMR = $this->getByID($MRID);

        $this->apiUpdateMR($oldMR->gitlabID, $oldMR->targetProject, $oldMR->mriid, $newMR);

        /* Change gitlab user ID to zentao account. */
        $gitlabUsers  = $this->gitlab->getUserIdAccountPairs($oldMR->gitlabID);
        $MR->assignee = zget($gitlabUsers, $MR->assignee, '');
        $MR->reviewer = zget($gitlabUsers, $MR->reviewer, '');

        /* Update MR in Zentao database. */
        $this->dao->update(TABLE_MR)->data($MR)
            ->where('id')->eq($MRID)
            ->autoCheck()
            ->exec();

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

        if(isset($rawMR->iid))
        {
            $map         = $this->config->MR->maps->sync;
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

            /*Sync GitLab Todo ZenTao Todo. */

            $gitlabTodoList = $this->apiTodoList($MR->gitlabID, $MR->targetProject);
            if($gitlabTodoList)
            {
                foreach($gitlabTodoList as $do)
                {
                    $todoDesc = $this->dao->select('*')
                                          ->from(TABLE_TODO)
                                          ->where('idvalue')
                                          ->eq($do->id)
                                          ->fetch();
                    if(empty($todoDesc))
                    {
                        $todo = new stdClass;
                        $todo->account       = $this->app->user->account;
                        $todo->assignedTo    = $this->app->user->account;
                        $todo->assignedBy    = $this->app->user->account;
                        $todo->date          = $do->target->created_at;
                        $todo->assignedDate  = $do->target->created_at;
                        $todo->begin         = $do->target->created_at;
                        $todo->end           = '';
                        $todo->type          = 'mrapprove';
                        $todo->idvalue       = $do->id;
                        $todo->pri           = 1;
                        $todo->name          = $do->target->title;
                        $todo->desc          = $do->target->description . "<br>" . $this->todoDescriptionLink($MR->gitlabID, $MR->targetProject);
                        $todo->private       = 0;
                        $todo->config        = 0;
                        $todo->finishedBy    = '';
                        $todo->finishedDate  = '';
                        $todo->closedBy      = '';
                        $todo->closedDate    = '0000-00-00 00:00:00';
                        $this->dao->insert(TABLE_TODO)->data($todo)->exec();
                    }
                }
            }
        }
        return $this->dao->findByID($MR->id)->from(TABLE_MR)->fetch();
    }

    /**
     * Get a list of to-do items.
     *
     * @docs   https://docs.gitlab.com/ee/api/todos.html
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function apiTodoList($gitlabID, $projectID)
    {
        $gitlab = $this->loadModel('gitlab')->getByID($gitlabID);
        if(!$gitlab) return '';
        $url = rtrim($gitlab->url, '/')."/api/v4/todos?project_id=$projectID&type=MergeRequest&private_token={$gitlab->token}";
        return json_decode(commonModel::http($url));
    }

    /**
     * Get a list of to-do items.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function todoDescriptionLink($gitlabID, $projectID)
    {
        $gitlab = $this->loadModel('gitlab')->getByID($gitlabID);
        if(!$gitlab) return '';
        return rtrim($gitlab->url, '/')."/dashboard/todos?project_id=$projectID&type=MergeRequest";
    }


    /**
     * Create MR by API.
     *
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#create-mr
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
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#list-project-merge-requests
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
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#get-single-mr
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
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
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
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#delete-a-merge-request
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
     * Accept MR by API.
     *
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#accept-mr
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  int    $MRID
     * @access public
     * @return object
     */
    public function apiAcceptMR($gitlabID, $projectID, $MRID)
    {
        $url = sprintf($this->gitlab->getApiRoot($gitlabID), "/projects/$projectID/merge_requests/$MRID");
        return json_decode(commonModel::http($url, $data, $options = array(CURLOPT_CUSTOMREQUEST => 'PUT')));
    }

    /**
     * Get MR diff versions by API.
     *
     * @docs   https://docs.gitlab.com/ee/api/merge_requests.html#get-mr-diff-versions
     * @param  object    $MR
     * @access public
     * @return object
     */
    public function getDiffs($MR)
    {
        $gitlab = $this->gitlab->getByID($MR->gitlabID);
        $fromProject = $MR->sourceProject;
        $toProject   = $MR->targetProject;

        $this->loadModel('repo');
        $repo = new stdclass;
        $repo->SCM      = 'GitLab';
        $repo->gitlab   = $gitlab->id;
        $repo->project  = $toProject;
        $repo->path     = sprintf($this->config->repo->gitlab->apiPath, $gitlab->url, $toProject);
        $repo->client   = $gitlab->url;
        $repo->password = $gitlab->token;

        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);

        $encoding = empty($encoding) ? $repo->encoding : $encoding;
        $encoding = strtolower(str_replace('_', '-', $encoding));
        return $scm->diff('', $fromProject, $toProject, $parse = true, $fromProject);
    }
}

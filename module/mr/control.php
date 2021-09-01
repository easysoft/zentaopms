<?php
class mr extends control
{
    /**
     * Browse mr.
     *
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($objectID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Save current URI to session. */
        $this->session->set('mrList', $this->app->getURI(true), 'repo');

        $this->view->title    = $this->lang->mr->common . $this->lang->colon . $this->lang->mr->browse;
        $this->view->MRList   = $this->mr->getList($orderBy, $pager);
        $this->view->orderBy  = $orderBy;
        $this->view->objectID = $objectID;
        $this->view->pager    = $pager;
        $this->display();
    }

    /**
     * Create MR function.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $result = $this->mr->create();
            return $this->send($result);
        }

        $this->view->title       = $this->lang->mr->create;
        $this->view->gitlabHosts = $this->loadModel('gitlab')->getPairs();
        $this->display();
    }

    /**
     * Edit MR function.
     *
     * @access public
     * @return void
     */
    public function edit($MRID)
    {
        if($_POST)
        {
            $result = $this->mr->update($MRID);
            return $this->send($result);
        }

        $MR = $this->mr->getByID($MRID);

        $branchList       = $this->loadModel('gitlab')->getBranches($MR->gitlabID, $MR->targetProject);
        $targetBranchList = array();
        foreach($branchList as $branch) $targetBranchList[$branch] = $branch;

        /* Fetch user list both in Zentao and current GitLab project. */
        $bindedUsers     = $this->gitlab->getUserIdRealnamePairs($MR->gitlabID);
        $rawProjectUsers = $this->gitlab->apiGetProjectUsers($MR->gitlabID, $MR->targetProject);
        $users           = array();
        foreach($rawProjectUsers as $rawProjectUser)
        {
            if(!empty($bindedUsers[$rawProjectUser->id])) $users[$rawProjectUser->id] = $bindedUsers[$rawProjectUser->id];
        }

        $gitlabUsers = $this->gitlab->getUserAccountIdPairs($MR->gitlabID);

        $this->view->title            = $this->lang->mr->edit;
        $this->view->MR               = $MR;
        $this->view->targetBranchList = $targetBranchList;
        $this->view->users            = array("" => "") + $users;
        $this->view->assignee         = zget($gitlabUsers, $MR->assignee, '');
        $this->view->reviewer         = zget($gitlabUsers, $MR->reviewer, '');

        $this->display();
    }

    /**
     * Delete a MR.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id, $confim = 'no')
    {
        if($confim != 'yes') die(js::confirm($this->lang->gitlab->confirmDelete, inlink('delete', "id=$id&confirm=yes")));

        $MR = $this->mr->getByID($id);

        $this->dao->delete()->from(TABLE_MR)->where('id')->eq($id)->exec();
        $this->mr->apiDeleteMR($MR->gitlabID, $MR->sourceProject, $MR->mriid);

        die(js::locate(inlink('browse'), 'parent'));
    }

    /**
     * View a MR.
     *
     * @access public
     * @return void
     */
    public function view($id)
    {
        $MR = $this->mr->getByID($id);
        if(isset($MR->gitlabID)) $rawMR = $this->mr->apiGetSingleMR($MR->gitlabID, $MR->targetProject, $MR->mriid);

        $this->view->title = $this->lang->mr->view;
        $this->view->MR    = $MR;
        $this->view->rawMR = isset($rawMR) ? $rawMR : false;

        $this->loadModel('gitlab');
        $sourceProject = $this->gitlab->apiGetSingleProject($MR->gitlabID, $MR->sourceProject);
        $targetProject = $this->gitlab->apiGetSingleProject($MR->gitlabID, $MR->targetProject);
        $sourceBranch  = $this->gitlab->apiGetSingleBranch($MR->gitlabID, $MR->sourceProject, $MR->sourceBranch);
        $targetBranch  = $this->gitlab->apiGetSingleBranch($MR->gitlabID, $MR->targetProject, $MR->targetBranch);

        $this->view->sourceProjectName = $sourceProject->name_with_namespace;
        $this->view->targetProjectName = $targetProject->name_with_namespace;
        $this->view->sourceProjectURL  = $sourceBranch ->web_url;
        $this->view->targetProjectURL  = $targetBranch ->web_url;

        /* Those variables are used to render $lang->mr->commandDocument. */
        $this->view->httpRepoURL = $sourceProject->http_url_to_repo;
        $this->view->branchPath  = $sourceProject->path_with_namespace . '-' . $rawMR->source_branch;

        $this->display();
    }

    /**
     * Crontab sync MR from GitLab API to Zentao database, default time 5 minutes to execute once.
     *
     * @access public
     * @return void
     */
    public function syncMR()
    {
        $this->mr->getList();

        if(dao::isError())
        {
            echo json_encode(dao::getError());
            return true;
        }

        echo 'success';
    }

    /**
     * Accept a MR.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function accept($MRID)
    {
        $MR = $this->mr->getByID($MRID);
        if(isset($MR->gitlabID)) $rawMR = $this->mr->apiAcceptMR($MR->gitlabID, $MR->targetProject, $MR->mriid);
        if(isset($rawMR->state) and $rawMR->state == 'merged') return array('result' => 'success', 'message' => $this->lang->mr->mergeSuccess);
        return array('result' => 'fail', 'message' => $this->lang->mr->mergeFailed);
    }

    /**
     * AJAX: Get MR target projects.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetMRTargetProjects($gitlabID, $projectID)
    {
        $this->loadModel('gitlab');

        /* First step: get forks. */
        $projects = $this->gitlab->apiGetForks($gitlabID, $projectID);

        /* Second step: get project itself. */
        $projects[] = $this->gitlab->apiGetSingleProject($gitlabID, $projectID);

        /* Last step: find its upstream recursively. */
        $project = $this->gitlab->apiGetUpstream($gitlabID, $projectID);
        if(!empty($project)) $projects[] = $project;

        while(!empty($project) and isset($project->id))
        {
            $project = $this->gitlab->apiGetUpstream($gitlabID, $project->id);
            if(empty($project)) break;
            $projects[] = $project;
        }

        if(!$projects) return $this->send(array('message' => array()));

        $options = "<option value=''></option>";
        foreach($projects as $project)
        {
            $options .= "<option value='{$project->id}' data-name='{$project->name}'>{$project->name_with_namespace}</option>";
        }

        $this->send($options);
    }

    /**
     * View diff between MR source and target branches.
     *
     * @param  int    $MRID
     * @access public
     * @return void
     */
    public function diff($MRID)
    {
        $MR      = $this->mr->getByID($MRID);
        $diffs   = $this->mr->getDiffs($MR);
        $arrange = $this->cookie->arrange ? $this->cookie->arrange : 'inline';

        if($this->server->request_method == 'POST')
        {
            if($this->post->arrange)
            {
                $arrange = $this->post->arrange;
                setcookie('arrange', $arrange);
            }
            if($this->post->encoding) $encoding = $this->post->encoding;
        }

        if($arrange == 'appose')
        {
            foreach($diffs as $diffFile)
            {
                if(empty($diffFile->contents)) continue;
                foreach($diffFile->contents as $content)
                {
                    $old = array();
                    $new = array();
                    foreach($content->lines as $line)
                    {
                        if($line->type != 'new') $old[$line->oldlc] = $line->line;
                        if($line->type != 'old') $new[$line->newlc] = $line->line;
                    }
                    $content->old = $old;
                    $content->new = $new;
                }
            }
        }

        $this->view->title   = $this->lang->mr->viewDiif;
        $this->view->diffs   = $diffs;
        $this->view->arrange = $arrange;
        $this->display();
    }
}

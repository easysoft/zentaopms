<?php
class mr extends control
{
    public function browse($objectID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('repo');

        $repoList = $this->repo->getList(0, $orderBy);
        foreach($repoList as $id => $repo)
        {
            if(strtolower($repo->SCM) != 'gitlab') unset($repoList[$id]);
        }

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal   = count($repoList);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $repoList   = array_chunk($repoList, $pager->recPerPage);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->browse;
        $this->view->position[] = $this->lang->repo->common;
        $this->view->position[] = $this->lang->repo->browse;

        $this->view->orderBy  = $orderBy;
        $this->view->objectID = $objectID;
        $this->view->pager    = $pager;
        $this->view->repoList = empty($repoList) ? $repoList: $repoList[$pageID - 1];;
        $this->view->products = $this->loadModel('product')->getPairs();

        $this->display();
    }

    public function list($repoID, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('gitlab');
        $gitlab = $this->mr->getGitlabProjectByRepo($repoID);
        $mrList = $this->mr->apiGetMRList($gitlab->gitlabID, $gitlab->projectID);

        $this->app->loadClass('pager', $static = true);
        $recTotal   = count($mrList);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $mrList     = array_chunk($mrList, $pager->recPerPage);

        $this->view->title    = $this->lang->mr->browse;
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
        $this->view->mrList = empty($mrList) ? $mrList: $mrList[$pageID - 1];;

        $this->display();
    }

    public function create()
    {
        $this->loadModel('repo');
        if($_POST)
        {
            $mrID = $this->mr->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $repoID));
            $link = helper::createLink('mr', 'browse', '', '', false);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $this->view->title       = $this->lang->mr->create;
        $this->view->gitlabHosts = $this->loadModel('gitlab')->getPairs();

        $this->display();
    }

    public function delete()
    {
    }

    public function update()
    {
    }

    /**
     * AJAX: Get forked projects.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetForkedProjects($gitlabID, $projectID)
    {
        $projects = $this->mr->apiGetForks($gitlabID, $projectID);

        if(!$projects) return $this->send(array('message' => array()));
        $projectIdList = $projectIdList ? explode(',', $projectIdList) : null;
        $options = "<option value=''></option>";
        foreach($projects as $project)
        {
            if(!empty($projectIdList) and $project and !in_array($project->id, $projectIdList)) continue;
            $options .= "<option value='{$project->id}' data-name='{$project->name}'>{$project->name_with_namespace}</option>";
        }

        return $this->send($options);
    }
}


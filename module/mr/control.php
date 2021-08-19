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
        $this->loadModel('mr');

        $mrList = $this->mr->getList(0, $orderBy);

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $recTotal   = count($mrList);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $mrList   = array_chunk($mrList, $pager->recPerPage);

        $this->view->title      = $this->lang->mr->common . $this->lang->colon . $this->lang->mr->browse;

        $this->view->orderBy  = $orderBy;
        $this->view->objectID = $objectID;
        $this->view->pager    = $pager;
        $this->view->mrList = empty($mrList) ? $mrList: $mrList[$pageID - 1];;
        $this->display();
    }

    public function list($mrID, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('gitlab');
        $gitlab = $this->mr->getGitlabProjectByRepo($mrID);
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
        $this->loadModel('mr');
        if($_POST)
        {
            $mrID = $this->mr->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $mrID));
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


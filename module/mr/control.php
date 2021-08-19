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

        $this->view->title    = $this->lang->mr->common . $this->lang->colon . $this->lang->mr->browse;
        $this->view->MRList   = $this->mr->getList(0, $orderBy, $pager);
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
            $this->mr->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title       = $this->lang->mr->create;
        $this->view->gitlabHosts = $this->loadModel('gitlab')->getPairs();

        $this->display();
    }

    /**
     * update
     *
     * @access public
     * @return void
     */
    public function update()
    {
    }

    /**
     * Delete a mr.
     *
     * @param  int    $MR
     * @access public
     * @return void
     */
    public function delete($MR, $confim = 'no')
    {
        if($confim != 'yes') die(js::confirm($this->lang->gitlab->confirmDelete, inlink('delete', "productID=$projectID&gitlabID=$gitlabID&MR=$MR&confirm=yes")));
        $this->mr->apiDeleteMR($MR);
        die(js::reload('parent'));
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

        $this->send($options);
    }
}

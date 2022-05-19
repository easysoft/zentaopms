<?php
class Project
{
    /**
     * __construct
     *
     * @param mixed $user
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $tester;

        $this->project = $tester->loadModel('project');
    }

    /**
     * Test start a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function start($projectID)
    {
        $_POST['realBegan'] = helper::today();
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status != 'suspended' and $oldProject->status != 'wait') return false;

        $changes = $this->project->start($projectID);

        return $this->project->getById($projectID);
    }

    /**
     * Test create project.
     *
     * @param  array $params
     * @access public
     * @return void
     */
    public function create($params)
    {
        $_POST = $params;

        $projectID = $this->project->create();
        unset($_POST);

        if(dao::isError()) return array('message' => dao::getError());

        return $this->project->getById($projectID);
    }

    /**
     * Update a project.
     *
     * @param  int   $projectID
     * @param  array $data
     * @access public
     * @return void
     */
    public function update($projectID, $data)
    {
        $_POST = $data;
        $this->project->update($projectID);

        unset($_POST);
        if(dao::isError()) return array('message' => dao::getError());

        return $this->project->getByID($projectID);
    }

    /**
     * Batch update projects.
     *
     * @param  array $data
     * @access public
     * @return void
     */
    public function batchUpdate($data)
    {
        $_POST = $data;
        $this->project->batchUpdate();

        unset($_POST);
        if(dao::isError()) return array('message' => dao::getError());

        return $this->project->getByIdList($data['projectIdList']);
    }

    /**
     *  Test get all the projects under the program set to which an project belongs
     *
     * @param object $project
     * @access public
     * @return void
     */
    public function getBrotherProjectsTest($project)
    {
        $projectIds = $this->project->getBrotherProjects($project);

        if(dao::isError()) return array('message' => dao::getError());
        return $projectIds;
    }
}

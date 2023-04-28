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
     * Handle code error message to custom error.
     *
     * @param  object $error
     * @access public
     * @return void
     */
    public function processCodeError($error)
    {
        throw Exception($error->getMessage());
    }

    /**
     * Call project model function and handle the exception.
     *
     * @param  string $method
     * @param  array  $params
     * @access public
     * @return string
     */
    public function triggerMethod($method, $params = array())
    {
        try
        {
            set_exception_handler(array($this, 'processCodeError'));
            return call_user_func_array(array($this->project, $method), $params);
        }
        catch(Throwable $error)
        {
            $errorInfo = $error->getMessage();
            if(preg_match('/Argument #\d+ (\(\$\w+\) must be of type [a-z]+),/', $errorInfo, $matches)) return $matches[1];
            return $errorInfo;
        }
    }

    /**
     * Test getByID function.
     *
     * @param  int    $projectID
     * @access public
     * @return string|bool|object
     */
    public function testGetByID($projectID)
    {
        return $this->triggerMethod('getByID', array('projectID' => $projectID));
    }

    /**
     * Test fetchProjectInfo function.
     *
     * @param  int    $projectID
     * @access public
     * @return string|bool|object
     */
    public function testFetchProjectInfo($projectID)
    {
        return $this->triggerMethod('fetchProjectInfo', array('projectID' => $projectID));
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
    public function create($project, $postData)
    {
        $projectID = $this->project->create($project, $postData);

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

    /**
     * Activate a project.
     *
     * @param int    $projectID
     * @param object $project
     * @access public
     * @return array $changes
     */
    public function activate($projectID, $project)
    {
        return $this->project->activate($projectID, $project);
    }

    /**
     * doActivate a project.
     *
     * @param int    $projectID
     * @param object $project
     * @access public
     * @return bool
     */
    public function doActivate($projectID, $project)
    {
        return $this->project->doActivate($projectID, $project);
    }
}

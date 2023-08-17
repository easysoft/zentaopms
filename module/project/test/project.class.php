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
     * Test getByShadowProduct function.
     *
     * @param  int    $productID
     * @access public
     * @return string|bool|object
     */
    public function testGetByShadowProduct($productID)
    {
        return $this->triggerMethod('getByShadowProduct', array('productID' => $productID));
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
     * @param  object $project
     * @param  object $oldProject
     * @access public
     * @return void
     */
    public function update(object $project, object $oldProject)
    {
        $this->project->update($project, $oldProject);

        if(dao::isError()) return dao::getError();

        return $this->project->getByID($oldProject->id);
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
        $this->project->batchUpdate($data);

        if(dao::isError()) return array('message' => dao::getError());

        return $this->project->getByIdList(array_keys($data));
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
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return array
     */
    public function activate($projectID, $project)
    {
        return $this->project->activate($projectID, $project);
    }

    /**
     * Do create a project.
     *
     * @param  object $project
     * @access public
     * @return array
     */
    public function doCreate(object $project)
    {
        $this->project->doCreate($project);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $project;
        }
    }

    /**
     * Do update a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return array
     */
    public function doUpdate(int $projectID, object $project)
    {
        $this->project->doUpdate($projectID, $project);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->project->getByID($projectID);
        }
    }

    /**
     * Do activate a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return bool
     */
    public function doActivate($projectID, $project)
    {
        return $this->project->doActivate($projectID, $project);
    }

    /**
     * Get budget with unit.
     *
     * @param  int        $budget
     * @access public
     * @return int|string
     */
    public function getBudgetWithUnit($budget)
    {
        return $this->project->getBudgetWithUnit($budget);
    }

    /**
     * Add user to project admins.
     *
     * @param  int        $budget
     * @access public
     * @return int|string
     */
    public function addProjectAdminTest($projectID)
    {
        $this->project->addProjectAdmin($projectID);

        return $this->project->dao->select('*')->from(TABLE_PROJECTADMIN)->fetch();
    }

    /**
     * Test fetchProjectList function.
     *
     * @param  int    $status
     * @param  bool   $involved
     * @access public
     * @return array
     */
    public function testFetchProjectList($status, $involved = false)
    {
        return $this->project->fetchProjectList($status, 'id_desc', $involved, null);
    }

    /**
     * Test getList function.
     *
     * @param  int    $status
     * @param  bool   $involved
     * @access public
     * @return array
     */
    public function testGetList($status, $involved = false)
    {
        return $this->project->fetchProjectList($status, 'id_desc', $involved, null);
    }

    /**
     * Test fetchProjectListByQuery function.
     *
     * @param  string $status
     * @param  int    $projectID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function testFetchProjectListByQuery($status, $projectID = 0, $orderBy = 'id_desc')
    {
        return $this->project->fetchProjectListByQuery($status, $projectID, $orderBy, 15, '');
    }

    /**
     * testCreateProduct
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $postData
     * @param  object $program
     * @access public
     * @return true|array
     */
    public function testCreateProduct($projectID, $project, $postData, $program)
    {
        $result = $this->project->createProduct($projectID, $project, $postData, $program);
        if(!$result) return dao::getError();

        return true;
    }

    /**
     * 根据项目状态和权限生成列表中操作列按钮。
     * Build table action menu for project browse page.
     *
     * @param  int    $projectID
     * @param  string $vision
     * @access public
     * @return string
     */
    public function buildActionListObject(int $projectID, string $vision = 'rnd')
    {
        $this->project->config->vision = $vision;

        $project = $this->project->getByID($projectID);
        $actions = $this->project->buildActionList($project);
        return current($actions);
    }

    /**
     * Test set menu.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function setMenuTest($projectID = 0)
    {
        $this->project->app->rawModule = 'project';
        $this->project->app->rawMethod = 'index';

        $this->project->setMenu($projectID);

        global $lang;
        return $lang->executionCommon;
    }
}

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
     * Test update plans.
     *
     * @param  int    $projectID
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function updatePlansTest(int $projectID, array $plans): array
    {
        $this->project->updatePlans($projectID, $plans);

        if(dao::isError()) return dao::getError();

        return $this->project->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->fetchAll();
    }

    /**
     * Test add plans.
     *
     * @param  int    $projectID
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function addPlansTest(int $projectID, array $plans): array
    {
        $this->project->addPlans($projectID, $plans);

        if(dao::isError()) return dao::getError();

        return $this->project->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->orderBy('order_asc')->fetchAll();
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
     * Test manage members.
     *
     * @param  int    $projectID
     * @param  array  $members
     * @access public
     * @return array
     */
    public function manageMembers(int $projectID, array $members): array
    {
        $this->project->manageMembers($projectID, $members);
        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->project->getTeamMembers($projectID);
        }
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
     * @access public
     * @return string
     */
    public function buildActionListObject(int $projectID)
    {
        $project = $this->project->getByID($projectID);
        $actions = $this->project->buildActionList($project);
        return current($actions);
    }

    /**
     * Test setMenu.
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

    /**
     * Test setMenuByModel.
     *
     * @param  string $model
     * @access public
     * @return string
     */
    public function setMenuByModelTest($model)
    {
        $this->project->setMenuByModel($model);

        global $lang;
        return $lang->executionCommon;
    }

    /**
     * Test setMenuByProduct.
     *
     * @param  string $model
     * @access public
     * @return string
     */
    public function setMenuByProductTest($hasProduct, $model)
    {
        global $lang;
        $this->project->setMenuByModel($model);
        $this->project->setMenuByProduct(11, $hasProduct, $model);

        $result = array(
            $model,
            isset($lang->project->menu->projectplan) ? 'projectplan' : '',
            isset($lang->project->menu->settings['subMenu']->module) ? 'settings' : '',
        );
        return implode('|', $result);
    }

    /**
     * Test build search form.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function buildSearchFormTest($queryID)
    {
        $this->project->buildSearchForm($queryID, 'searchUrl');

        return $_SESSION['projectsearchParams']['queryID'];
    }

    /**
     * Test build search form.
     *
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function buildProjectBuildSearchFormTest(int $projectID, int $productID, string $type)
    {
        global $app, $config;
        unset($config->build->search['module']);
        unset($config->build->search['fields']['branch']);
        unset($config->build->search['fields']['execution']);

        $app->rawModule = 'projectBuild';
        $app->rawMethod = 'browse';
        $result = $this->project->buildProjectBuildSearchForm(array(), 0, $projectID, $productID, $type);
        if(!$result) return false;

        $result = array($config->build->search['module']);
        if(isset($config->build->search['fields']['branch']))    $result[] = 'branch';
        if(isset($config->build->search['fields']['execution'])) $result[] = 'execution';
        return implode('|', $result);
    }

    /**
     * 创建项目后，创建默认的项目主库。
     * Create doclib after create a project.
     *
     * @param  int    $projectID
     * @param  int    $programID
     * @access public
     * @return object|bool
     */
    public function createDocLibTest(int $projectID, int $programID): object|bool
    {
        $program = new stdclass();
        if($programID)
        {
            global $tester;
            $program = $tester->loadModel('program')->getByID($programID);
        }

        $project = $this->project->getByID($projectID);

        $this->project->createDocLib($projectID, $project, $program);

        return $this->project->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('project')->andWhere('project')->eq($projectID)->fetch();
    }

    /**
     * 获取创建项目时选择的产品数量。
     * Get products count from post.
     *
     * @param  int    $projectID
     * @param  array  $products
     * @access public
     * @return void
     */
    public function getLinkedProductsCountTest(int $projectID, array $products)
    {
        $project = $this->project->getByID($projectID);

        $rawdata = new stdclass();
        $rawdata->products = $products;

        return $this->project->getLinkedProductsCount($project, $rawdata);
    }

    /**
     * 创建产品后，创建默认的产品主库。
     * Create doclib after create a product.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function createProductDocLibTest(int $productID): object
    {
        $this->project->createProductDocLib($productID);
        return $this->project->dao->select('*')->from(TABLE_DOCLIB)->where('type')->eq('product')->andWhere('product')->eq($productID)->fetch();
    }

    /**
     * 构造批量更新项目的数据。
     * Build bathc update project data.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function buildBatchUpdateProjectsTest(array $projectIdList): array
    {
        $oldProjects = $this->project->getByIdList($projectIdList);
        $newProjects = array();
        foreach($oldProjects as $projectID => $project)
        {
            $newProjects[$projectID] = $project;
            $newProjects[$projectID]->name = '更新' . $project->name;
            $newProjects[$projectID]->PM   = 'admin';
        }
        return $this->project->buildBatchUpdateProjects($newProjects, $oldProjects);
    }

    /**
     * 如果是无产品项目，更新影子产品信息。
     * Update shadow product.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function updateShadowProductTest(int $projectID)
    {
        $oldProject = $this->project->getByID($projectID);
        $newProject = clone $oldProject;
        $newProject->name = '更新' . $oldProject->name;
        $newProject->acl  = 'open';

        $this->project->updateShadowProduct($newProject, $oldProject);
        $products = $this->project->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.project')->eq($projectID)
            ->fetchAll();

        return $products;
    }

    /**
     * 更新任务的起止日期。
     * Update start and end date of tasks.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function updateTasksStartAndEndDateTest(int $projectID, string $type = 'expand')
    {
        $oldProject = $this->project->getByID($projectID);

        $project = clone $oldProject;
        $project->begin = $type = 'expand' ? '2020-01-01' : '2020-12-01';
        $project->end   = $type = 'expand' ? '2022-12-01' : '2021-12-01';

        $tasks = $this->project->dao->select('id,status,estStarted,deadline')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->fetchAll();

        if(!empty($tasks)) $oldTask = current($tasks);

        $this->project->updateTasksStartAndEndDate($tasks, $oldProject, $project);

        $changes = array();
        if(isset($oldTask))
        {
            $task = $this->project->dao->select('id,status,estStarted,deadline')->from(TABLE_TASK) ->where('id')->eq($oldTask->id)->fetch();
            $changes = common::createChanges($oldTask, $task);
        }
        return $changes;
    }

    /**
     * 关联其他项目集下的产品。
     * Link products of other programs.
     *
     * @param  int    $projectID
     * @param  array  $productIdList
     * @access public
     * @return bool
     */
    public function linkOtherProductsTest(int $projectID, array $productIdList): bool
    {
        $members = $this->project->getTeamMembers($projectID);

        $_POST['otherProducts'] = $productIdList;
        $_POST['stageBy']       = 'product';

        return $this->project->linkOtherProducts($projectID, $members);
    }

    /**
     * 关联项目所属项目集下的产品。
     * Link products of current program of the project.
     *
     * @param  int    $projectID
     * @param  array  $products
     * @param  array  $branches
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function linkProductsTest(int $projectID, array $products = array(), array $branches = array(), array $plans = array()): array
    {
        $members            = $this->project->getTeamMembers($projectID);
        $oldProjectProducts = $this->project->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchGroup('product', 'branch');
        if(!empty($branches)) $_POST['branch'] = $branches;
        if(!empty($plans)) $_POST['branch'] = $plans;

        $this->project->linkProducts($projectID, $products, $oldProjectProducts, $members);

        return $this->project->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchAll();
    }

    /**
     * 更新项目关联的产品信息。
     * Update products of a project.
     *
     * @param  int    $projectID
     * @param  array  $products
     * @param  array  $otherProducts
     * @access public
     * @return array
     */
    public function updateProductsTest(int $projectID, array $products = array(), array $otherProducts = array()): array
    {
        $_POST['stageBy'] = 'product';
        if(!empty($otherProducts)) $_POST['otherProducts'] = $otherProducts;
        if(!empty($products)) $_POST['products'] = $products;

        $this->project->updateProducts($projectID, $products);

        return $this->project->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchAll();
    }
}

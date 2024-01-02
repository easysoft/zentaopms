<?php
class programTest
{
    /**
     * __construct
     *
     * @param  mixed $user
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $tester;

        $this->program = $tester->loadModel('program');
    }

    /**
     * Get list by search.
     *
     * @param  string $orderBy
     * @param  int    $queryID
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getListBySearchTest($orderBy = 'id_asc', $queryID = 0, $sql = '')
    {
        if(!empty($sql)) $_SESSION['programQuery'] = $sql;

        return $this->program->getListBySearch($orderBy, $queryID);
    }

    /**
     * Get pairs.
     *
     * @param  bool $isQueryAll
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairsTest($isQueryAll = false, $orderBy = 'id_desc')
    {
        return $this->program->getPairs($isQueryAll, $orderBy);
    }

    /**
     * Get pairs by id list.
     *
     * @param  string $programIDList
     * @access public
     * @return array
     */
    public function getPairsByListTest($programIDList = array())
    {
        return $this->program->getPairsByList((array)$programIDList);
    }

    /**
     * Get parent pairs.
     *
     * @param  string $model
     * @param  string $mode
     * @param  bool   $showRoot
     * @access public
     * @return array
     */
    public function getParentPairsTest($model = '', $mode = 'noclosed', $showRoot = true)
    {
        return $this->program->getParentPairs($model, $mode, $showRoot);
    }

    /**
     * Get the product associated with the program.
     *
     * @param  int          $programID
     * @param  string       $mode
     * @param  string       $status
     * @param  string|array $append
     * @param  int|string   $shadow
     * @param  bool         $withProgram
     * @access public
     * @return array
     */
    public function getProductPairsTest($programID = 0, $mode = 'assign', $status = 'all', $append = '', $shadow = 0, $withProgram = false)
    {
        return $this->program->getProductPairs($programID, $mode, $status, $append, $shadow, $withProgram);
    }

    /**
     * Test create program.
     *
     * @param  array $data
     * @access public
     * @return object
     */
    public function createTest($data)
    {
        $programID = $this->program->create((object)$data);

        if(dao::isError()) return array('message' => dao::getError());

        $program = $this->program->getByID($programID);

        return $program;
    }

    /**
     * Get parent PM.
     *
     * @param  array  $programIdList
     * @access public
     * @return array
     */
    public function getParentPMTest($programIdList)
    {
        return $this->program->getParentPM($programIdList);
    }

    /**
     * Test updateChildUserView method.
     *
     * @param  int    $programID
     * @param  array  $accounts
     * @access public
     * @return void
     */
    public function updateChildUserViewTest($programID, $accounts = array())
    {
        $stakeHolder = $this->program->updateChildUserView($programID, $accounts);

        return $this->program->dao->select('*')->from(TABLE_USERVIEW)->where('account')->eq('test2')->fetch('programs');
    }

    /**
     * Create default program.
     *
     * @access public
     * @return void
     */
    public function createDefaultProgramTest()
    {
        $programID = $this->program->createDefaultProgram();

        return $programID > 0;
    }

    /**
     * Test get list.
     *
     * @param  mixed  $status
     * @param  string $orderBy
     * @param  string $type       top|child
     * @param  mixed  $idList
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getListTest($status = 'all', $orderBy = 'id_asc', $type = '', $idList = array(), $pager = NULL)
    {
        $this->program->cookie->showClosed = 'ture';
        $programs = $this->program->getList($status, $orderBy, $type, $idList, $pager);

        if(dao::isError()) return array('message' => dao::getError());

        return $programs;
    }


    /**
     * Test update program.
     *
     * @param  mixed  $proguamID
     * @param  mixed  $data
     * @access public
     * @return void
     */
    public function updateTest($programID, $data)
    {
        $_POST = $data;
        $this->program->update($programID, (object)$data);
        if(dao::isError()) return array('message' => dao::getError());

        return $this->program->getByID($programID);
    }

    /**
     * Get program by id.
     *
     * @param  int    $proguamID
     * @access public
     * @return object
     */
    public function getByIDTest($programID)
    {
        $program = $this->program->getByID($programID);
        if(dao::isError()) return array('message' => dao::getError());

        return $program;
    }

    /**
     * Get budget left.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getBudgetLeftTest($programID)
    {
        $program = $this->program->getByID($programID);
        $budget  = $this->program->getBudgetLeft($program);

        if(dao::isError()) return array('message' => dao::getError());

        return $budget;
    }

    /**
     * Set tree path.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function setTreePathTest($programID)
    {
        $this->program->setTreePath($programID);
        $program = $this->program->getByID($programID);

        return $program;
    }

    /**
     * Check clickable.
     *
     * @param  int    $programID
     * @param  string $status
     * @access public
     * @return int
     */
    public function isClickableTest($programID, $status)
    {
        return $this->program->isClickable($programID, $status);
    }

    /**
     * 该项目集下是否未关闭的子项目集或项目。
     * Judge whether there is an unclosed programs or projects.
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function hasUnfinishedChidlrenTest(int $programID): bool
    {
        $program = $this->program->getByID($programID);
        return $this->program->hasUnfinishedChildren($program);
    }

    /*
     * get involved programs.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getInvolvedProgramsTest($account)
    {
        return $this->program->getInvolvedPrograms($account);
    }

    /**
     * Get team member pairs .
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getTeamMemberPairsTest($programID)
    {
        return $this->program->getTeamMemberPairs($programID);
    }

    /**
     * Test save state.
     *
     * @param  int    $programID
     * @param  array  $programs
     * @access public
     * @return array
     */
    public function checkAccessTest($programID = 0, $programs = array())
    {
        return $this->program->checkAccess($programID, $programs);
    }

    /**
     * 修改子项目集和项目的层级。
     * Modify the subProgram and project grade.
     *
     * @param  int     $programID
     * @param  int     $parentID
     * @param  string  $oldPath
     * @param  int     $oldGrade
     * @access public
     * @return array
     */
    public function processNodeTest(int $programID, int $parentID, string $oldPath, int $oldGrade): array
    {
        $oldChildGrade = $this->program->dao->select('grade')->from(TABLE_PROGRAM)
            ->where('path')->like("{$oldPath}%")
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade')
            ->fetch('grade');

        $this->program->processNode($programID, $parentID, $oldPath, $oldGrade);

        $newChildGrade = $this->program->dao->select('grade')->from(TABLE_PROGRAM)
            ->where('path')->like("{$oldPath}%")
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade')
            ->fetch('grade');

        return array('old' => empty($oldChildGrade) ? 0 : $oldChildGrade, 'new' => empty($newChildGrade) ? 0 : $newChildGrade);
    }

    /**
     * 测试 fixLinkedProduct。
     * Test fixLinkedProduct.
     *
     * @param  int    $programID
     * @param  int    $parentID
     * @param  int    $oldParentID
     * @param  string $oldPath
     * @access public
     * @return int
     */
    public function fixLinkedProductTest(int $programID, int $parentID, int $oldParentID, string $oldPath): int
    {
        $newTopProgram = $this->program->getTopByID($programID);
        $this->program->fixLinkedProduct($programID, $parentID, $oldParentID, $oldPath);
        $newProduct = $this->program->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($newTopProgram)->fetch('id');

        return empty($newProduct) ? 0 : $newProduct;
    }

    /**
     * 获取看板统计的相关数据。
     * Get Kanban statistics data.
     *
     * @access public
     * @return array
     */
    public function getKanbanStatisticDataTest(): array
    {
        $programs = $this->program->getTopPairs('noclosed');
        return $this->program->getKanbanStatisticData($programs);
    }

    /**
     * 获取项目/执行的所属项目集ID。
     * Get the programID of the project/execution.
     *
     * @param  int    $objectID
     * @param  int    $exist
     * @access public
     * @return int
     */
    public function getProgramIDByObjectTest(int $objectID, int $exist): int
    {
        $objects = array();
        $object  = $this->program->loadModel('project')->getByID($objectID);
        if($exist)
        {
            $objects[$objectID] = $object;
            if(in_array($object->type, array('sprint', 'stage', 'kanban')))
            {
                $project = $this->program->loadModel('project')->getByID($object->project);
                $objects[$object->project] = $project;
            }
        }

        return $this->program->getProgramIDByObject($objectID, $object, $objects);
    }

    /**
     * 关闭一个项目集。
     * Close a program.
     *
     * @param  int    $programID
     * @param  array  $postData
     * @access public
     * @return array
     */
    public function closeTest(int $programID, array $postData): array
    {
        $now        = helper::now();
        $oldProgram = $this->program->getByID($programID);

        $program = new stdclass();
        $program->status         = 'close';
        $program->closedDate     = $now;
        $program->closedBy       = 'admin';
        $program->lastEditedDate = $now;
        $program->lastEditedBy   = 'admin';
        foreach($postData as $field => $value) $program->{$field} = $value;

        $changes = $this->program->close($program, $oldProgram);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 激活一个项目集。
     * Activate a program.
     *
     * @param  int    $programID
     * @param  array  $postData
     * @access public
     * @return array
     */
    public function activateTest(int $programID, array $postData): array
    {
        $now        = helper::now();
        $oldProgram = $this->program->getByID($programID);

        $program = new stdclass();
        $program->status         = 'doing';
        $program->lastEditedDate = $now;
        $program->lastEditedBy   = 'admin';
        $program->realEnd         = null;
        foreach($postData as $field => $value) $program->{$field} = $value;

        $changes = $this->program->activate($program, $oldProgram);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * Test updateOrder method.
     *
     * @param  int    $programID
     * @param  int    $order
     * @access public
     * @return void
     */
    public function updateOrderTest($programID, $order)
    {
        $this->program->updateOrder($programID, $order);
        return $this->program->getByID($programID);
    }

    /**
     * 构建项目视角中项目集的操作数据。
     * Build actions map for program.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function buildProgramActionsMapTest(int $programID): array
    {
        $program = $this->program->getByID($programID);
        return $this->program->buildProgramActionsMap($program);
    }

    /**
     * Test getNormalActions method.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getNormalActionsTest($programID)
    {
        $program = $this->program->getByID($programID);
        return $this->program->getNormalActions($program);
    }

    /**
     * 构建项目视角中项目的操作数据。
     * Build actions map for project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function buildProjectActionsMapTest(int $projectID): array
    {
        $project = $this->program->getByID($projectID);
        return $this->program->buildProjectActionsMap($project);
    }

    /**
     * 构造项目集列表的操作列数据。
     * Build actions data.
     *
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function buildActionsTest(int $objectID): array
    {
        $object = $this->program->getByID($objectID);
        return $this->program->buildActions($object);
    }

    /**
     * 获取任务的统计数据。
     * Get task stats.
     *
     * @param  array $projectIdList
     * @access public
     * @return array
     */
    public function getTaskStatsTest(array $projectIdList): array
    {
        $summary = $this->program->getTaskStats($projectIdList);
        if(dao::isError()) return dao::getError();

        return $summary;
    }

    /**
     * 更新项目集的统计数据。
     * Update stats of program.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function updateStatsTest(array $projectIdList): array
    {
        $this->program->updateStats($projectIdList);
        if(dao::isError()) return dao::getError();

        return $this->program->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('project')->beginIF(!empty($projectIdList))->andWhere('id')->in($projectIdList)->fi()->fetchAll('id');
    }

    /**
     * 更新项目集的进度。
     * Update program progress.
     *
     * @access public
     * @return array
     */
    public function updateProcessTest(): array
    {
        $this->program->updateProcess();
        if(dao::isError()) return dao::getError();

        return $this->program->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('program')->fetchAll('id');
    }

    /**
     * 刷新项目集的统计数据。
     * Refresh stats fields(estimate,consumed,left,progress) of program, project, execution.
     *
     * @access public
     * @return array
     */
    public function refreshStatsTest(): array
    {
        $this->program->refreshStats(true);
        if(dao::isError()) return dao::getError();

        return $this->program->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('program')->fetchAll('id');
    }

    /**
     * 该项目集下是否有未关闭的子项目集或项目。
     * Judge whether there is an unclosed programs or projects.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function hasUnfinishedChildrenTest(int $programID): int
    {
        $program = $this->program->getByID($programID);
        return $this->program->hasUnfinishedChildren($program);
    }
}

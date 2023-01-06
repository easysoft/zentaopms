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
     * Get switcher.
     *
     * @param  int   $programID
     * @access public
     * @return string
     */
    public function getSwitcherTest($programID = 0)
    {
        return $this->program->getSwitcher($programID);
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
    public function getPairsByListTest($programIDList = '')
    {
        return $this->program->getPairsByList($programIDList);
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
        $_POST = $data;

        $programID = $this->program->create();

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
     * Create stakeholder.
     *
     * @param  int    $programID
     * @param  array  $accounts
     * @access public
     * @return void
     */
    public function createStakeholderTest($programID, $accounts = array())
    {
        $_POST['accounts'] = $accounts;
        $stakeHolder = $this->program->createStakeholder($programID);

        return $this->program->getStakeholdersByPrograms($programID);
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
     * @param  object $pager
     * @param  string $type       top|child
     * @param  mixed  $idList
     * @access public
     * @return void
     */
    public function getListTest($status = 'all', $orderBy = 'id_asc', $pager = NULL, $type = '', $idList = '')
    {
        $this->program->cookie->showClosed = 'ture';
        $programs = $this->program->getList($status, $orderBy, $pager, $type, $idList);

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
        $this->program->update($programID);
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
     * Get children.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getChildrenTest($programID)
    {
        return $this->program->getChildren($programID);
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
     * Has unfinished.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function hasUnfinishedTest($programID)
    {
        $program = $this->program->getByID($programID);
        return $this->program->hasUnfinished($program);
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

    /*
     * Get Tree menu.
     *
     * @param  string $programID
     * @access public
     * @return array
     */
    public function getTreeMenuTest($programID)
    {
        return $this->program->getTreeMenu($programID);
    }

    /**
     * Get top pairs.
     *
     * @param  string $model
     * @param  string $mode
     * @param  bool   $isQueryAll
     * @access public
     * @return array
     */
    public function getTopPairsTest($model = '', $mode = '', $isQueryAll = false)
    {
        return $this->program->getTopPairs($model, $mode, $isQueryAll);
    }

    /**
     * Get kanban group.
     *
     * @access public
     * @return array
     */
    public function getKanbanGroupTest()
    {
        return $this->program->getKanbanGroup();
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
     * Get project stats.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectStatsTest($programID = 0, $browseType = 'undone', $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        return $this->program->getProjectStats($programID, $browseType, $queryID, $orderBy, $pager);
    }

    /**
     * Get project list.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $pager
     * @param  int    $programTitle
     * @param  int    $involved
     * @access public
     * @return array
     */
    public function getProjectListTest($programID = 0, $browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null, $programTitle = 0, $involved = 0)
    {
        return $this->program->getProjectList($programID, $browseType, $queryID, $orderBy, $pager, $programTitle, $involved);
    }

    /**
     * Get progress list.
     *
     * @access public
     * @return array
     */
    public function getProgressListTest()
    {
        return $this->program->getProgressList();
    }

    /**
     * Get stakeholders.
     *
     * @param  int    $programID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStakeholdersTest($programID, $orderBy = 'id_desc')
    {
        return $this->program->getStakeholders($programID, $orderBy);
    }

    /**
     * Test save state.
     *
     * @param  int    $programID
     * @param  array  $programs
     * @access public
     * @return array
     */
    public function saveStateTest($programID = 0, $programs = array())
    {
        return $this->program->saveState($programID, $programs);
    }

    /**
     * Test build operate menu.
     *
     * @param  int    $programID
     * @access public
     * @return string
     */
    public function buildOperateMenuTest($programID = 0)
    {
        $program = $this->program->getByID($programID);
        if(empty($program)) return '0';

        return $this->program->buildOperateMenu($program);
    }

    /**
     * Test set menu.
     *
     * @param  int    $programID
     * @access public
     * @return string
     */
    public function setMenuTest($programID = 0)
    {
        $program = $this->program->getByID($programID);
        if(empty($program)) return '0';

        $this->program->setMenu($programID);

        global $lang;
        return strip_tags($lang->switcherMenu);
    }
}

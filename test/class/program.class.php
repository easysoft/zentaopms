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

        su('admin');
        $this->program = $tester->loadModel('program');
    }

    /**
     * Test create program.
     *
     * @param  array $data
     * @access public
     * @return object 
     */
    public function create($data)
    {
        $_POST = $data;

        $programID = $this->program->create();

        if(dao::isError()) return array('message' => dao::getError());

        $program = $this->program->getById($programID);

        return $program;
    }

    /**
     * createStakeholder
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function createStakeholder($programID)
    {
        $_POST['accounts'] = array('dev1', 'dev2');
        $stakeHolder = $this->program->createStakeholder($programID);

        return $this->program->getStakeholdersByPrograms($programID);
    }

    /**
     * getBudgetLeft
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getBudgetLeft($programID)
    {
        $program = $this->program->getById(1);

        return $this->program->getBudgetLeft($program);
    }

    /**
     * getBudgetUnitList
     *
     * @access public
     * @return void
     */
    public function getBudgetUnitList()
    {
        global $app;
        $app->loadConfig('project');
        $app->loadLang('project');

        return $this->program->getBudgetUnitList();
    }

    /**
     * getById
     *
     * @param  iont   mixed $programID
     * @access public
     * @return void
     */
    public function getById($programID)
    {
        if(empty($this->program->getById($programID)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getById($programID);
        }
    }

    /**
     * getChildren
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getChildren($programID)
    {
        $programInfo = $this->program->getChildren($programID);
        if(empty($programInfo))
        {
            return array('code' => 'fail' , 'message' => 'Not Found');
        }
        else
        {
            return $programInfo;
        }
    }

    /**
     * Test get pairs by list.
     *
     * @param  string|array $programIDList
     * @access public
     * @return void
     */
    public function getPairsByList($programIDList = '')
    {
        if(empty($this->program->getPairsByList($programIDList)))
        {
            return array('code' => '404', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getPairsByList($programIDList);
        }
    }

    /**
     * Test get list.
     *
     * @param  mixed  $status
     * @access public
     * @return void
     */
    public function getList($status = 'all', $orderBy = 'id_asc', $pager = NULL)
    {
        $this->program->cookie->showClosed = 'ture';
        $programs = $this->program->getList($status, $orderBy, $pager);

        if(dao::isError()) return array('message' => dao::getError());

        return $programs;
    }


    /**
     * getParentPairs
     *
     * @access public
     * @return void
     */
    public function getParentPairs()
    {
        return $this->program->getParentPairs();
    }

    /**
     * getParentPM
     *
     * @param  array  mixed $programIdList
     * @access public
     * @return void
     */
    public function getParentPM($programIdList)
    {
        return $this->program->getParentPM($programIdList);
    }

    /**
     * getProductPairsByID
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function getProductPairsByID($programID = 0)
    {
        $program   = $this->program->getByID($programID);
        if(empty($program)) return array('message' => 'Not Found');
        return $this->program->getProductPairs($programID, 'assign', 'all');
    }

    /**
     * getProductPairsByMod
     *
     * @param  string $mode
     * @access public
     * @return void
     */
    public function getProductPairsByMod($mode = 'assign')
    {
        return $this->program->getProductPairs(1, $mode, 'noclosed');
    }

    /**
     * getProductPairsByStatus
     *
     * @param  string $status
     * @access public
     * @return void
     */
    public function getProductPairsByStatus($status = 'all')
    {
        return $this->program->getProductPairs(1, 'assign', $status);
    }

    /**
     * getProgressList
     *
     * @access public
     * @return void
     */
    public function getProgressList()
    {
        return $this->program->getProgressList();
    }

    /**
     * getStatsByProgramID
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function getStatsByProgramID($programID = 0)
    {
        return count($this->program->getProjectStats($programID));
    }

    /**
     * getStatsByStatus
     *
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function getStatsByStatus($browseType = 'all')
    {
        $projects = $this->program->getProjectStats('0', $browseType);

        if(!$projects) return 0;
        foreach($projects as $project)
        {
            if($project->status != $browseType and $browseType != 'all' and $browseType != 'undone') return 0;
            if($browseType == 'undone' and ($project->status != ('wait' or 'doing'))) return 0;
        }

        return count($projects);
    }

    /**
     * getStatsByOrder
     *
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getStatsByOrder($orderBy = 'id_desc')
    {
        $projects = $this->program->getProjectStats('0', 'all', '0', $orderBy);

        return checkOrder($projects, $orderBy);
    }

    /**
     * getStatsAddProgramTitle
     *
     * @param  int    $programTitle
     * @access public
     * @return void
     */
    public function getStatsAddProgramTitle($programTitle = 0)
    {
        return $this->program->getProjectStats('0', 'all', '0', 'id_desc', '', $programTitle);
    }

    /**
     * getStatsByInvolved
     *
     * @param  int    $involved
     * @param  string $count
     * @access public
     * @return void
     */
    public function getStatsByInvolved($involved = 0, $count = '')
    {
        $projects = $this->program->getProjectStats('0', 'all', '0', 'id_desc', '', '0', $involved);

        if($count == 'count') return count($projects);
        return $projects;
    }

    /**
     * getByPrograms
     *
     * @param  int    $programIdList
     * @access public
     * @return void
     */
    public function getByPrograms($programIdList = 0)
    {
        $stakeHolders = $this->program->getStakeholdersByPrograms($programIdList);

        return $stakeHolders;
    }

    /**
     * getByOrder
     *
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getByOrder($orderBy = 'id_desc')
    {
        $stakeholders = $this->program->getStakeholders(2, $orderBy);

        return checkOrder($stakeholders, $orderBy);
    }

    /**
     * getTopPairs
     *
     * @param  string $count
     * @access public
     * @return void
     */
    public function getTopPairs($count = '')
    {
        if($count == 'count') return count($this->program->getTopPairs());
        return $this->program->getTopPairs();
    }

    /**
     * getUnfinished
     *
     * @param  mixed  $programID
     * @access public
     * @return void
     */
    public function getUnfinished($programID)
    {
        $program = $this->program->getById($programID);

        return $this->program->hasUnfinished($program);
    }

    /**
     * setTreePath
     *
     * @param  mixed  $programID
     * @access public
     * @return void
     */
    public function setTreePath($programID)
    {
        $programPath = $this->program->setTreePath($programID);
        if($programPath)
        {
            return $this->program->getById($programID);
        }
        else
        {
            return 0;
        }
    }

    /**
     * update
     *
     * @param  mixed  $proguamID
     * @param  mixed  $data
     * @access public
     * @return void
     */
    public function update($programID, $data)
    {
        global $app;

        $_POST = $data;
        $result = $this->program->update(10);
        if(dao::isError()) return array('message' => dao::getError());

        $app->dbh->query("UPDATE " . TABLE_PROGRAM . " SET name = '" . $result[0]['old']. "' where id = '" . $programID . "'");
        return $result;
    }

    /**
     * updateProgram
     *
     * @param  mixed  $programID
     * @param  int    $status
     * @access public
     * @return void
     */
    public function updateProgram($programID, $status = 0)
    {
        $data = array(
            'parent' => '0',
            'name' => '测试更新项目集十',
            'begin' => '2020-10-10',
            'end' => '2020-10-11',
            'acl' => 'private',
            'budget' => '100',
            'budgetUnit' => 'CNY',
            'whitelist' => array('dev10', 'dev12')
        );

        switch($status)
        {
        case '1': // 项目集名称已经存在时
            $data['name'] = '项目集1';
            break;
        case '2': // 当计划开始为空时更新项目集信息
            $data['begin'] = '';
            break;
        case '3': // 当计划完成为空时更新项目集信息
            $data['end'] = '';
            break;
        case '4': // 当计划完成小于计划开始时
            $data['end'] = '2020-01-01';
            break;
        case '5': // 项目集开始时间小于父项目集时
            $data['parent'] = '9';
            $data['begin']  = '2019-01-01';
            break;
        default: // 更新id为10的项目集信息
        }
        return $this->update($programID, $data);
    }

    /**
     * Test process node method.
     *
     * @param  int    $programID
     * @param  int    $parentID
     * @param  string $oldPath
     * @param  int    $oldGrade
     * @access public
     * @return void
     */
    public function processNode($programID, $parentID, $oldPath, $oldGrade)
    {
        $programs = $this->program->processNode($programID, $parentID, $oldPath, $oldGrade);
        return $programs;
    }

}
?>

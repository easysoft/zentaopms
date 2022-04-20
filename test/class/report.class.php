<?php
class reportTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('report');
    }

    /**
     * Test compute percent of every item.
     *
     * @param  array  $datas
     * @access public
     * @return string
     */
    public function computePercentTest($datas)
    {
        $objects = $this->objectModel->computePercent($datas);

        if(dao::isError()) return dao::getError();

        $percents = '';
        foreach($objects as $moduleID => $object) $percents .= "$moduleID:$object->percent;";
        return $percents;
    }

    /**
     * Test create json data of single charts.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function createSingleJSONTest($executionID)
    {
        global $tester;
        $this->execution = $tester->loadModel('execution');

        $execution = $this->execution->getByID($executionID);
        $sets      = $this->execution->getBurnDataFlot($executionID, 'left');
        $dateList  = $this->execution->getDateList($execution->begin, $execution->end, 'noweekend', 0, 'Y-m-d');

        $objects = $this->objectModel->createSingleJSON($sets, $dateList[0]);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test convert date format.
     *
     * @param  array  $dateList
     * @param  string $format
     * @access public
     * @return string
     */
    public function convertFormatTest($dateList, $format = 'Y-m-d')
    {
        $objects = $this->objectModel->convertFormat($dateList, $format = 'Y-m-d');

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }

    /**
     * Test get executions.
     *
     * @param int $begin
     * @param int $end
     * @access public
     * @return void
     */
    public function getExecutionsTest($begin = 0, $end = 0)
    {
        $begin = $begin != 0 ? date('Y-m-d', strtotime(date('Y-m-d') . $begin)) : 0;
        $end   = $end != 0 ? date('Y-m-d', strtotime(date('Y-m-d') . $end)) : 0;
        $objects = $this->objectModel->getExecutions($begin, $end);

        if(dao::isError()) return dao::getError();

        $executions = '';
        foreach($objects as $executionID => $execution) $executions .= "$executionID:$execution->estimate,$execution->consumed,$execution->projectName;";
        return $executions;
    }

    /**
     * Test get products.
     *
     * @param  string $conditions
     * @param  string $storyType
     * @access public
     * @return string
     */
    public function getProductsTest($conditions, $storyType = 'story')
    {
        $objects = $this->objectModel->getProducts($conditions, $storyType);

        if(dao::isError()) return dao::getError();

        $planCount = 0;
        foreach($objects as $object)
        {
            if(isset($object->plans)) $planCount += count($object->plans);
        }
        return 'product:' . count($objects) . ';plan:' . $planCount;
    }

    /**
     * Test get bugs.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return array
     */
    public function getBugsTest($begin, $end, $product, $execution)
    {
        $begin = date('Y-m-d', strtotime(date('Y-m-d') . $begin));
        $end   = date('Y-m-d', strtotime(date('Y-m-d') . $end));
        $objects = $this->objectModel->getBugs($begin, $end, $product, $execution);

        if(dao::isError()) return dao::getError();

        $count = array();
        foreach($objects as $user => $types)
        {
            $count[$user] = '';
            foreach($types as $type => $typeCount) $count[$user] .= "$type:$typeCount;";
        }
        return $count;
    }

    public function getWorkloadTest($dept = 0, $assign = 'assign')
    {
        $objects = $this->objectModel->getWorkload($dept = 0, $assign = 'assign');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get bug assign.
     *
     * @access public
     * @return void
     */
    public function getBugAssignTest()
    {
        $objects = $this->objectModel->getBugAssign();

        if(dao::isError()) return dao::getError();

        $count = array();
        foreach($objects as $user => $object) $count[$user] = $object['total']['count'];
        return $count;
    }

    public function getSysURLTest()
    {
        $objects = $this->objectModel->getSysURL();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserBugsTest()
    {
        $objects = $this->objectModel->getUserBugs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserTasksTest()
    {
        $objects = $this->objectModel->getUserTasks();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserTodosTest()
    {
        $objects = $this->objectModel->getUserTodos();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserTestTasksTest()
    {
        $objects = $this->objectModel->getUserTestTasks();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserYearLoginsTest($accounts, $year)
    {
        $objects = $this->objectModel->getUserYearLogins($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserYearActionsTest($accounts, $year)
    {
        $objects = $this->objectModel->getUserYearActions($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserYearContributionsTest($accounts, $year)
    {
        $objects = $this->objectModel->getUserYearContributions($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserYearTodosTest($accounts, $year)
    {
        $objects = $this->objectModel->getUserYearTodos($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserYearEffortsTest($accounts, $year)
    {
        $objects = $this->objectModel->getUserYearEfforts($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserYearProductsTest($accounts, $year)
    {
        $objects = $this->objectModel->getUserYearProducts($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserYearExecutionsTest($accounts, $year)
    {
        $objects = $this->objectModel->getUserYearExecutions($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get status stat that is all time, include story, task and bug.
     *
     * @access public
     * @return array
     */
    public function getAllTimeStatusStatTest()
    {
        $objects = $this->objectModel->getAllTimeStatusStat();

        if(dao::isError()) return dao::getError();

        $types = array();
        foreach($objects as $type => $status)
        {
            $types[$type] = '';
            foreach($status as $statusType => $statusCount) $types[$type] .= "$statusType:$statusCount;";
        }
        return $types;
    }

    public function getYearObjectStatTest($accounts, $year, $objectType)
    {
        $objects = $this->objectModel->getYearObjectStat($accounts, $year, $objectType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getYearCaseStatTest($accounts, $year)
    {
        $objects = $this->objectModel->getYearCaseStat($accounts, $year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getYearMonthsTest($year)
    {
        $objects = $this->objectModel->getYearMonths($year);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStatusOverviewTest($objectType, $statusStat)
    {
        $objects = $this->objectModel->getStatusOverview($objectType, $statusStat);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectStatusOverviewTest($accounts = array())
    {
        $objects = $this->objectModel->getProjectStatusOverview($accounts = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get output data for API.
     *
     * @param  string $accounts
     * @param  string $year
     * @access public
     * @return string
     */
    public function getOutput4APITest($accounts, $year)
    {
        $objects = $this->objectModel->getOutput4API($accounts, $year);

        if(dao::isError()) return dao::getError();

        $output = '';
        foreach($objects as $objectType => $object) $output .= "$objectType:" . $object['total'] . ";";
        return $output;
    }

    public function getProjectExecutionsTest()
    {
        $objects = $this->objectModel->getProjectExecutions();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}

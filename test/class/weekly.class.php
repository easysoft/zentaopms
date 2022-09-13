<?php
class weeklyTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel  = $tester->loadModel('weekly');
         $this->projectModel = $tester->loadModel('project');
    }


    /**
     * GetPageNav
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return string
     */

    public function getPageNavTest($projectID, $date)
    {
        $project = $this->projectModel->getById($projectID);
        $pageNav = $this->objectModel->getPageNav($project, $date);

        if(dao::isError()) return dao::getError();

        $start_str = mb_strpos($pageNav,"ass='btn'>") + mb_strlen("class='btn'>");
        $end_str   = mb_strpos($pageNav,"</a>") - $start_str;
        $objects   = mb_substr($pageNav,$start_str,$end_str);
        return $objects;
    }

    /**
     * GetWeekPairs
     *
     * @param  int    $begin
     * @param  int    $end
     * @access public
     * @return array
     */

    public function getWeekPairsTest($begin, $end)
    {
        switch($begin)
        {
        case '1':
           $begin = date('Y-m-d');
           break;
        case '2':
           $begin = date('Y-m-d', strtotime(date('Y-m-d')."- 6 days"));
           break;
        case '3':
           $begin = date('Y-m-d', strtotime(date('Y-m-d') . "+ 6 days"));
           break;
        case '':
           $begin = '';
           break;
        }

        switch($begin)
        {
        case '1':
           $end = date('Y-m-d');
           break;
        case '2':
           $end = date('Y-m-d', strtotime(date('Y-m-d')."- 6 days"));
           break;
        case '3':
           $end = date('Y-m-d', strtotime(date('Y-m-d') . "+ 6 days"));
           break;
        case '':
           $end = '';
           break;
        }

        $objects = $this->objectModel->getWeekPairs($begin, $end);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * GetFromDB
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return object
     */

    public function getFromDBTest($project, $date)
    {
        $objects = $this->objectModel->getFromDB($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

     /**
     * Save data.
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */

    public function saveTest($project, $date)
    {
        $objects = $this->objectModel->save($project, $date);

        if(dao::isError()) return dao::getError();

        $weekly = $this->objectModel->getFromDB($project, $date);
        return $weekly;
    }

    /**
     * GetWeekSN
     *
     * @param  string $begin
     * @param  string $date
     * @access public
     * @return int
     */

    public function getWeekSNTest($begin, $date)
    {
        $objects = $this->objectModel->getWeekSN($begin, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get monday for a date.
     *
     * @param  string $date
     * @access public
     * @return date
     */

    public function getThisMondayTest($date)
    {
        $objects = $this->objectModel->getThisMonday($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * GetThisSunday
     *
     * @param  string $date
     * @access public
     * @return date
     */

    public function getThisSundayTest($date)
    {
        $objects = $this->objectModel->getThisSunday($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * GetLastDay
     *
     * @param  string $date
     * @access public
     * @return string
     */

    public function getLastDayTest($date)
    {
        $objects = $this->objectModel->getLastDay($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getStaff
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return array
     */
    public function getStaffTest($project, $date = '')
    {
        $objects = $this->objectModel->getStaff($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getFinished
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getFinishedTest($project, $date = '')
    {
        $objects = $this->objectModel->getFinished($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getPostponed
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getPostponedTest($project, $date = '')
    {
        $objects = $this->objectModel->getPostponed($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getTasksOfNextWeek
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getTasksOfNextWeekTest($project, $date = '')
    {
        $objects = $this->objectModel->getTasksOfNextWeek($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getWorkloadByType
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return object
     */
    public function getWorkloadByTypeTest($project, $date = '')
    {
        $objects = $this->objectModel->getWorkloadByType($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getPlanedTaskByWeek
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return array
     */
    public function getPlanedTaskByWeekTest($project, $date = '')
    {
        $objects = $this->objectModel->getPlanedTaskByWeek($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getPVEV
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return int
     */
    public function getPVEVTest($projectID, $date = '')
    {
        $objects = $this->objectModel->getPVEV($projectID, $date);

        if(dao::isError()) return dao::getError();

        return $objects['PV'] . ',' . $objects['EV'];
    }

    /**
     * Test get AC data.
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return int
     */
    public function getACTest($project, $date = '')
    {
        $objects = $this->objectModel->getAC($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get SV data.
     *
     * @param  int    $ev
     * @param  int    $pv
     * @access public
     * @return int
     */
    public function getSVTest($ev, $pv)
    {
        $objects = $this->objectModel->getSV($ev, $pv);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getCV
     *
     * @param  int    $ev
     * @param  int    $ac
     * @access public
     * @return int
     */
    public function getCVTest($ev, $ac)
    {
        $objects = $this->objectModel->getCV($ev, $ac);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getTips
     *
     * @param  string $type
     * @param  int    $data
     * @access public
     * @return string
     */
    public function getTipsTest($type = 'progress', $data = 0)
    {
        $objects = $this->objectModel->getTips($type, $data);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}

<?php
class weeklyTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('weekly');
    }

    public function getPageNavTest($project, $date)
    {
        $objects = $this->objectModel->getPageNav($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getWeekPairsTest($begin, $end = '')
    {
        $objects = $this->objectModel->getWeekPairs($begin, $end = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getFromDBTest($project, $date)
    {
        $objects = $this->objectModel->getFromDB($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveTest($project, $date)
    {
        $objects = $this->objectModel->save($project, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getWeekSNTest($begin, $date)
    {
        $objects = $this->objectModel->getWeekSN($begin, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getThisMondayTest($date)
    {
        $objects = $this->objectModel->getThisMonday($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getThisSundayTest($date)
    {
        $objects = $this->objectModel->getThisSunday($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

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
     * Test getPV
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return int
     */
    public function getPVTest($projectID, $date = '')
    {
        $objects = $this->objectModel->getPV($projectID, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get EV data.
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return int
     */
    public function getEVTest($projectID, $date = '')
    {
        $objects = $this->objectModel->getEV($projectID, $date);

        if(dao::isError()) return dao::getError();

        return $objects;
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

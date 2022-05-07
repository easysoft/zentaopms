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
     * @param  int    $date
     * @access public
     * @return string
     */

    public function getPageNavTest($projectID, $date)
    {
        if($date == 1)
        {
            $date = date('Y-m-d');
        }
        elseif($date == 2)
        {
            $date = date('Y-m-d', strtotime(date('Y-m-d')."- 6 days"));
        }
        else
        {
            $date = date('Y-m-d', strtotime(date('Y-m-d') . "+ 6 days"));
        }

        $project = $this->projectModel->getById($projectID);
        $pageNav = $this->objectModel->getPageNav($project, $date);

        if(dao::isError()) return dao::getError();

        $objects = strstr($pageNav,"</div>",true);
        return $objects;
    }

    /**
     * GetWeekPairs
     *
     * @param  int    $begin
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
     * @param  int    $date
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
     * @param  int    $date
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
     * @param  int    $begin
     * @param  int    $date
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
     * @param  int $date
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
     * @param  int    $date
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
     * @param  int    $date
     * @access public
     * @return string
     */

    public function getLastDayTest($date)
    {
        $objects = $this->objectModel->getLastDay($date);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStaffTest($project, $date = '')
    {
        $objects = $this->objectModel->getStaff($project, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getFinishedTest($project, $date = '', $pager = null)
    {
        $objects = $this->objectModel->getFinished($project, $date = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPostponedTest($project, $date = '')
    {
        $objects = $this->objectModel->getPostponed($project, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTasksOfNextWeekTest($project, $date = '')
    {
        $objects = $this->objectModel->getTasksOfNextWeek($project, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getWorkloadByTypeTest($project, $date = '')
    {
        $objects = $this->objectModel->getWorkloadByType($project, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPlanedTaskByWeekTest($project, $date = '')
    {
        $objects = $this->objectModel->getPlanedTaskByWeek($project, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPVTest($projectID, $date = '')
    {
        $objects = $this->objectModel->getPV($projectID, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getEVTest($projectID, $date = '')
    {
        $objects = $this->objectModel->getEV($projectID, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getACTest($project, $date = '')
    {
        $objects = $this->objectModel->getAC($project, $date = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSVTest($ev, $pv)
    {
        $objects = $this->objectModel->getSV($ev, $pv);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCVTest($ev, $ac)
    {
        $objects = $this->objectModel->getCV($ev, $ac);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTipsTest($type = 'progress', $data = 0)
    {
        $objects = $this->objectModel->getTips($type = 'progress', $data = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}

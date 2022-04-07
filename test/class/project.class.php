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
     * checkStatus
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function checkStatus($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status != 'closed') return false;

        $change = $this->project->activate($projectID);

        $project = $this->project->getById($projectID);
        if($project->status != 'doing') return false;

        return true;
    }

    /**
     * checkStatusOff
     *
     * @param  int    mixed $projectID
     * @param  array  $closeTime
     * @access public
     * @return void
     */
    public function checkStatusOff($projectID, $closeTime = array())
    {
        $checkStatus = array();
        $closeEnd = array('realEnd' => '');
        foreach($closeEnd as $filed => $defaultValue) $_POST[$filed] = $defaultValue;
        foreach($closeTime as $key => $value) $_POST[$key] = $value;

        $oldProject = $this->project->getById($projectID);
        if($oldProject->status == 'suspended' or $oldProject->status == 'closed') return false;

        $change = $this->project->close($projectID);

        $project = $this->project->getById($projectID);
        if($project->status != 'closed') return false;

        return true;
    }

    /**
     * getListByOrder
     *
     * @param  string mixed $orderBy
     * @access public
     * @return void
     */
    public function getListByOrder($orderBy)
    {
        $projects = $this->project->getOverviewList('byStatus', 'wait', $orderBy);
        return checkOrder($projects, $orderBy);
    }

    /**
     * getByID
     *
     * @param  mixed  $projectID
     * @access public
     * @return void
     */
    public function getByID($projectID)
    {
        return $this->project->getOverviewList('byID', $projectID);
    }

    /**
     * getTeamMemberPairs
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getTeamMemberPairs($projectID)
    {
        $members = array_filter($this->project->getTeamMemberPairs($projectID));
        if(empty($members)) return false;
        return count($members);
    }

    /**
     * checkMembers
     *
     * @param  int    mixed $members
     * @param  string mixed $users
     * @access public
     * @return void
     */
    public function checkMembers($members, $users)
    {
        foreach($users as $user)
        {
            if(!isset($members[$user])) return false;
        }
        return true;
    }

    /**
     * getTeamMembers
     *
     * @param  int     mixed $projectID
     * @param  striong mixed $users
     * @param  boolean mixed $tutorial
     * @access public
     * @return void
     */
    public function getTeamMembers($projectID, $users, $tutorial = false)
    {
        if($tutorial) define('TUTORIAL', true);
        $members = $this->project->getTeamMembers($projectID);
        if(empty($members)) return false;
        if(!empty($users))  $this->checkMembers($members, $users);
        return count($members);
    }

    /**
     * checkStatusBegin
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function checkStatusBegin($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status != 'suspended' and $oldProject->status != 'wait') return false;

        $change = $this->project->start($projectID);
        $project = $this->project->getById($projectID);
        if($project->status != 'doing') return false;

        return true;
    }

    /**
     * checkStatusStop
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function checkStatusStop($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status == 'suspended' or $oldProject->status == 'closed') return false;

        $change = $this->project->suspend($projectID);
        $project = $this->project->getById($projectID);
        if($project->status != 'suspended') return false;

        return true;
    }

    /**
     * getExecutionData
     *
     * @param  int    mixed $projectID
     * @param  string $type
     * @access public
     * @return void
     */
    public function getExecutionData($projectID, $type = '')
    {
        $table = 'zt_project';
        $executions = $this->project->getDataByProject($table, $projectID, $type);
        if(empty($executions)) return false;
        return $executions;
    }

    /**
     * getBuildData
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getBuildData($projectID)
    {
        $builds = $this->project->getDataByProject(TABLE_BUILD, $projectID);
        if(empty($builds)) return false;
        return $builds;
    }

    /**
     * getReleaseData
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getReleaseData($projectID)
    {
        $releases = $this->project->getDataByProject(TABLE_RELEASE, $projectID);
        if(empty($releases)) return false;
        return $releases;
    }

    /**
     * getByStatusExe
     *
     * @param  string mixed $status
     * @access public
     * @return void
     */
    public function getByStatusExe($status)
    {
        $executions = $this->project->getStats(0, $status);
        if(empty($executions)) return false;
        if($status != 'all')
        {
            foreach($executions as $execution)
            {
                if($execution->status != $status) return false;
            }
        }
        return count($executions);
    }

    /**
     * getByProject
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getByProject($projectID)
    {
        $executions = $this->project->getStats($projectID, 'all');
        if(empty($executions)) return false;
        foreach($executions as $execution)
        {
            if($execution->project != $projectID) return false;
        }
        return count($executions);
    }

    /**
     * getStatData
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getStatData($projectID)
    {
        $projects = $this->project->getStatData($projectID);
        return $projects;
    }

    /**
     * getTotalStoriesByProject
     *
     * @param  int    $projectID
     * @param  array  $productIdList
     * @param  string $type
     * @param  string $status
     * @access public
     * @return void
     */
    public function getTotalStoriesByProject($projectID = 0, $productIdList = array(), $type = 'story', $status = 'all')
    {
        $projects = $this->project->getTotalStoriesByProject($projectID = 0, $productIdList = array(), $type = 'story', $status = 'all');
        return $projects;
    }

    /**
     * Test create project.
     * 
     * @param  array $params 
     * @access public
     * @return void
     */
    public function create($params)
    {
        $_POST = $params;

        $projectID = $this->project->create();
        unset($_POST);

        if(dao::isError()) return array('message' => dao::getError());

        return $this->project->getById($projectID);
    }

    /**
     * saveState
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function saveState($projectID)
    {
        $projects = $this->project->getPairsByProgram();
        $object   = $this->project->saveState($projectID, $projects);
        return $object;
    }
}

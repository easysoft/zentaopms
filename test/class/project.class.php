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
    public function __construct($user)
    {
        global $tester;

        su($user);
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
     * checkHasChildren
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function checkHasChildren($projectID)
    {
        return $this->project->checkHasChildren($projectID);
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
     * checkBudgetUnitList
     *
     * @param  string $checkList
     * @param  'USD' => '美元') $'USD' => '美元')
     * @access public
     * @return void
     */
    public function checkBudgetUnitList($checkList = array('CNY' => '人民币', 'USD' => '美元'))
    {
        $budgetList = $this->project->getBudgetUnitList();
        foreach($budgetList as $enBudget => $zhBudget)
        {
            if($checkList[$enBudget] != $zhBudget) return false;
        }
        return true;
    }

    /**
     * getByIdList
     *
     * @param  array  mixed $projectIdList
     * @param  int    mixed $count
     * @access public
     * @return void
     */
    public function getByIdList($projectIdList, $count)
    {
        $projectList = $this->project->getByIdList($projectIdList);
        if(count($projectList) != $count) return false;
        return $projectList;
    }

    /**
     * getProjectByID
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getProjectByID($projectID)
    {
        return $this->project->getByID($projectID);
    }

    /**
     * getProgramByID
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getProgramByID($programID)
    {
        return $this->project->getByID($programID, 'program');
    }


    /**
     * getSprintByID
     *
     * @param  int    mixed $sprintID
     * @access public
     * @return void
     */
    public function getSprintByID($sprintID)
    {
        return $this->project->getByID($sprintID, 'sprint');
    }

    /**
     * getStageByID
     *
     * @param  int    mixed $stageID
     * @access public
     * @return void
     */
    public function getStageByID($stageID)
    {
        return $this->project->getByID($stageID, 'stage');
    }

    /**
     * getKanbanByID
     *
     * @param  int    mixed $kanbanID
     * @access public
     * @return void
     */
    public function getKanbanByID($kanbanID)
    {
        return $this->project->getByID($kanbanID, 'kanban');
    }

    /**
     * getBystatus
     *
     * @param  string mixed $status
     * @access public
     * @return void
     */
    public function getBystatus($status)
    {
        $projects = $this->project->getOverviewList('byStatus', $status);
        if(!$projects)
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        if($status == 'undone') $status = 'wait,doing,suspended';

        foreach($projects as $project)
        {
            if(strpos(",$status,", $project->status) === false)
            {
                $result = array();
                $result['code']    = 'fail';
                $result['message'] = 'Error data.';

                return $result;
            }
        }

        return count($projects);
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
     * getByIdListFind
     *
     * @param  array  mixed $IDList
     * @access public
     * @return void
     */
    public function getByIdListFind($IDList)
    {
        $projects = $this->project->getPairsByIdList($IDList);
        if(empty($projects))
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        foreach($projects as $projectID => $projectName)
        {
            if(!empty($IDList) and !in_array($projectID, $IDList))
            {
                $result = array();
                $result['code']    = 'fail';
                $result['message'] = 'Error Data.';

                return $result;
            }
        }

        return count($projects);
    }

    /**
     * getParentName
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getParentName($projectID)
    {
        $program = $this->project->getParentName($projectID);
        if(empty($program)) return false;
        return $program;
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
     * getByProgram
     *
     * @param  int    mixed $programID
     * @access public
     * @return void
     */
    public function getByProgram($programID)
    {
        $projects = $this->project->getPairsByProgram($programID);
        if(empty($projects))
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        return count($projects);
    }

    /**
     * getByStatusPairs
     *
     * @param  string mixed $status
     * @access public
     * @return void
     */
    public function getByStatusPairs($status)
    {
        $projects = $this->project->getPairsByProgram(2, $status);
        if(empty($projects))
        {
            $result = array();
            $result['code']    = 'fail';
            $result['message'] = 'No data.';

            return $result;
        }

        return count($projects);
    }

    /**
     * getConsumed
     *
     * @param  array  mixed $projectIdList
     * @access public
     * @return void
     */
    public function getConsumed($projectIdList)
    {
        $projects = $this->project->getProjectsConsumed($projectIdList, $time = '');
        return $projects;
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
     * getTotalBugBy
     *
     * @param  array  mixed $projectIdList
     * @param  string mixed $status
     * @access public
     * @return void
     */
    public function getTotalBugBy($projectIdList, $status)
    {
        $projects = $this->project->getTotalBugByProject($projectIdList, $status);
        return $projects;
    }

    /**
     * getWorkHour
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function getWorkHour($projectID)
    {
        $projects = $this->project->getWorkhour($projectID);
        return $projects;
    }

    /**
     * getInfoList
     *
     * @param  string mixed $status
     * @access public
     * @return void
     */
    public function getInfoList($status)
    {
        $projects = $this->project->getInfoList($status);
        return $projects;
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
     * saveState
     *
     * @param  int    mixed $projectID
     * @access public
     * @return void
     */
    public function saveState($projectID)
    {
        $projects = $this->project->getPairsByProgram();
        $object = $this->project->saveState($projectID, $projects);
        return $object;
    }

}
?>

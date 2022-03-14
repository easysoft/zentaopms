<?php
class Project
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     *      * Check project status after activate a project.▫
     *           *▫
     *                * @param  int    $projectID▫
     *                     * @access public
     *                          * @return bool
     *                               */
    public function checkStatus($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status != 'closed') return false;

        $change = $this->project->activate($projectID);

        $project = $this->project->getById($projectID);
        if($project->status != 'doing') return false;

        return true;
    }

    public function checkHasChildren($projectID)
    {
        return $this->project->checkHasChildren($projectID);
    }

    /**
     * Check project status after close a project.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
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
     * Check budget unit list.
     *
     * @param  string $checkList▫
     * @access public
     * @return bool
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
     *      * Check projectList get from getByIdList.
     *           *
     *                * @param  array  $projectIdList
     *                     * @param  int    $count
     *                          * @access public
     *                               * @return array
     *                                    */
    public function getByIdList($projectIdList, $count)
    {
        $projectList = $this->project->getByIdList($projectIdList);
        if(count($projectList) != $count) return false;
        return $projectList;
    }

    /**
     *      * Get project by ID.
     *           *
     *                * @param  int $projectID
     *                     * @access public
     *                          * @return object
     *                               */
    public function getProjectByID($projectID)
    {
        return $this->project->getByID($projectID);
    }

    /**
     *      * Get program by ID.
     *           *
     *                * @param  int $programID
     *                     * @access public
     *                          * @return object
     *                               */
    public function getProgramByID($programID)
    {
        return $this->project->getByID($programID, 'program');
    }

    /**
     *      * Get sprint by ID.
     *           *
     *                * @param  int $sprintID
     *                     * @access public
     *                          * @return object
     *                               */

    public function getSprintByID($sprintID)
    {
        return $this->project->getByID($sprintID, 'sprint');
    }

    /**
     *      * Get stage by ID.
     *           *
     *                * @param  int $stageID
     *                     * @access public
     *                          * @return object
     *                               */
    public function getStageByID($stageID)
    {
        return $this->project->getByID($stageID, 'stage');
    }

    /**
     *      * Get Kanban by ID,
     *           *
     *                * @param  int $kanbanID
     *                     * @access public
     *                          * @return void
     *                               */
    public function getKanbanByID($kanbanID)
    {
        return $this->project->getByID($kanbanID, 'kanban');
    }

    /**
     *      * Get project by status.
     *           *
     *                * @param  string $status
     *                     * @access public
     *                          * @return int
     *                               */
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
     *      * Get project list by order.
     *           *
     *                * @param  string $orderBy
     *                     * @access public
     *                          * @return void
     *                               */
    public function getListByOrder($orderBy)
    {
        $projects = $this->project->getOverviewList('byStatus', 'wait', $orderBy);
        return checkOrder($projects, $orderBy);
    }

    /**
     *      * Get project by ID.
     *           *
     *                * @param  int $projectID
     *                     * @access public
     *                          * @return void
     *                               */
    public function getByID($projectID)
    {
        return $this->project->getOverviewList('byID', $projectID);
    }

    /**
     *      * Get project pairs by ID list.
     *           *
     *                * @param  array $IDList
     *                     * @access public
     *                          * @return int
     *                               */
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
     *      * Get parentName.
     *           *▫
     *                * @param  int    $projectID▫
     *                     * @access public
     *                          * @return array
     *                               */
    public function getParentName($projectID)
    {
        $program = $this->project->getParentName($projectID);
        if(empty($program)) return false;
        return $program;
    }

    /**
     *      * Get team member pairs.▫
     *           *▫
     *                * @param  int    $projectID▫
     *                     * @access public
     *                          * @return int
     *                               */
    public function getTeamMemberPairs($projectID)
    {
        $members = array_filter($this->project->getTeamMemberPairs($projectID));
        if(empty($members)) return false;
        return count($members);
    }

    /**
     *     /**
     *          * Check members.▫
     *               *▫
     *                    * @param  array  $members▫
     *                         * @param  array  $users▫
     *                              * @access public
     *                                   * @return bool
     *                                        */
    public function checkMembers($members, $users)
    {
        foreach($users as $user)
        {
            if(!isset($members[$user])) return false;
        }
        return true;
    }

    /**
           * Get team memberm.
                *▫
                     * @param  int    $projectID▫
                          * @param  array  $users▫
                               * @param  bool   $tutorial▫
                                    * @access public
                                         * @return int
                                              */
    public function getTeamMembers($projectID, $users, $tutorial = false)
    {
        if($tutorial) define('TUTORIAL', true);
        $members = $this->project->getTeamMembers($projectID);
        if(empty($members)) return false;
        if(!empty($users))  $this->checkMembers($members, $users);
        return count($members);
    }

    /*
     * Check project status after start a project.
     *▫
     * @param  int    $projectID▫
     * @access public
     * @return bool
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

    /*
     * Check project status after suspend a project.
     *
     * @param  int    $projectID▫
     * @access public
     * @return bool
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

    public function getExecutionData($projectID, $type = '')
    {
        $table = 'zt_project';
        $executions = $this->project->getDataByProject($table, $projectID, $type);
        if(empty($executions)) return false;
        return $executions;
    }

    /**
     *      * Get builds from getDataByProject.
     *           *
     *                * @param  int    $projectID
     *                     * @access public
     *                          * @return array
     *                               */
    public function getBuildData($projectID)
    {
        $builds = $this->project->getDataByProject(TABLE_BUILD, $projectID);
        if(empty($builds)) return false;
        return $builds;
    }

    /**
     *      * Get releases from getDataByProject.
     *           *
     *                * @param  int    $projectID
     *                     * @access public
     *                          * @return array
     *                               */
    public function getReleaseData($projectID)
    {
        $releases = $this->project->getDataByProject(TABLE_RELEASE, $projectID);
        if(empty($releases)) return false;
        return $releases;
    }

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
     * Get project pairs by status.
     *
     * @param  string $status
     * @access public
     * @return int
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
     * Get project list by order.
     *
     * @param  string $orderBy
     * @access public
     * @return int
     */

    public function getConsumed($projectIdList)
    {
        $projects = $this->project->getProjectsConsumed($projectIdList, $time = '');
        return $projects;
    }

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

    /*
     * Get executions by project.
     *
     * @param  int    $projectID
     * @access public
     * @return int
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

    /*
     * Get executions by order.
     *
     * @param  string $orderBy

     * @access public
     * @return bool
     */

    public function getTotalBugBy($projectIdList, $status)
    {
        $projects = $this->project->getTotalBugByProject($projectIdList, $status);
        return $projects;
    }

    public function getWorkHour($projectID)
    {
        $projects = $this->project->getWorkhour($projectID);
    }

}
?>

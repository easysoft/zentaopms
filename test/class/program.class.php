<?php
class Program
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->program = $tester->loadModel('program');
    }

    function create($data)
    {
        global $app;

        $_POST = '';
        $_POST = $data;

        $programID = $this->program->create();

        if(dao::isError()) return array('message' => dao::getError());

        $program = $this->program->getById($programID);

        $app->dbh->query("DELETE FROM ". TABLE_PROGRAM ." where name = '" . $data['name']. "'");
        return $program;
    }
    function createData($status)
    {
        $data = array(
            'parent'     => 0,
            'name'       => '测试新增项目集一',
            'budget'     => '',
            'budgetUnit' => 'CNY',
            'begin'      => '2022-01-12',
            'end'        => '2022-02-12',
            'desc'       => '测试项目集描述',
            'acl'        => 'private',
            'whitelist'  => ''
        );

        switch($status)
        {
        case '1': // 创建新项目集
            break;
        case '2': // 项目集名称为空时
            $data['name']   = '';
            break;
        case '3': // 项目集的开始时间为空
            $data['begin']  = '';
            break;
        case '4': // 项目集的完成时间为空
            $data['end']    = '';
            break;
        case '5': // 项目集的计划完成时间大于计划开始时间
            $data['end']    = '2022-01-10';
            break;
        case '6': // 项目集的完成日期大于父项目集的完成日期
            $data['parent'] = '1';
            $data['begin']  = '2018-01-01';
            $data['end']    = '2022-02-10';
            break;
        default:
        }
        return $this->create($data);
    }

    public function createStakeholder($programID)
    {
        $_POST['accounts'] = array('dev1', 'dev2');
        $stakeHolder = $this->program->createStakeholder($programID);

        return $this->program->getStakeholdersByPrograms($programID);
    }

    public function getBudgetLeft($programID)
    {
        $program = $this->program->getById(1);

        return $this->program->getBudgetLeft($program);
    }

    public function getBudgetUnitList()
    {
        global $app;
        $app->loadConfig('project');
        $app->loadLang('project');

        return $this->program->getBudgetUnitList();
    }

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

    public function getInvolvedPrograms($account)
    {
        return $this->program->getInvolvedPrograms($account);
    }

    public function getPairsByList($programIDList = '')
    {
        if(empty($this->program->getPairsByList($programIDList)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getPairsByList($programIDList);
        }
    }

    public function getPairs()
    {
        $programs = $this->program->getPairs();
        if(!$programs) return 0;
        return $programs;
    }

    public function getCount()
    {
        return count($this->program->getPairs());
    }

    public function getParentPairs()
    {
        return $this->program->getParentPairs();
    }

    public function getCount1()
    {
        return count($this->program->getParentPairs());
    }

    public function getParentPM($programIdList)
    {
        return $this->program->getParentPM($programIdList);
    }

    public function getProductPairsByID($programID = 0)
    {
        $program   = $this->program->getByID($programID);
        if(empty($program)) return array('message' => 'Not Found');
        return $this->program->getProductPairs($programID, 'assign', 'all');
    }

    public function getProductPairsByMod($mode = 'assign')
    {
        return $this->program->getProductPairs(1, $mode, 'noclosed');
    }

    public function getProductPairsByStatus($status = 'all')
    {
        return $this->program->getProductPairs(1, 'assign', $status);
    }

    public function getCount2($programID = 0, $mode = 'assign', $status = 'all')
    {
        return count($this->program->getProductPairs($programID, $mode, $status));
    }

    public function getProgressList()
    {
        return $this->program->getProgressList();
    }

    public function getCount3()
    {
        return count($this->program->getProgressList());
    }

    public function getStatsByProgramID($programID = 0)
    {
        return count($this->program->getProjectStats($programID));
    }

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

    public function getStatsByOrder($orderBy = 'id_desc')
    {
        $projects = $this->program->getProjectStats('0', 'all', '0', $orderBy);

        return checkOrder($projects, $orderBy);
    }

    public function getStatsAddProgramTitle($programTitle = 0)
    {
        return $this->program->getProjectStats('0', 'all', '0', 'id_desc', '', $programTitle);
    }

    public function getStatsByInvolved($involved = 0, $count = '')
    {
        $projects = $this->program->getProjectStats('0', 'all', '0', 'id_desc', '', '0', $involved);

        if($count == 'count') return count($projects);
        return $projects;
    }

    public function getByPrograms($programIdList = 0)
    {
        $stakeHolders = $this->program->getStakeholdersByPrograms($programIdList);

        return $stakeHolders;
    }

    public function getCount4($programIdList = 0)
    {
        $stakeHolders = $this->program->getStakeholdersByPrograms($programIdList);

        return count($stakeHolders);
    }

    public function getByID1($programID = 0)
    {
        $stakeholders = $this->program->getStakeholders($programID);

        return $stakeholders;
    }

    public function getByOrder($orderBy = 'id_desc')
    {
        $stakeholders = $this->program->getStakeholders(2, $orderBy);

        return checkOrder($stakeholders, $orderBy);
    }

    public function getCount5($programID = 0)
    {
        $stakeholders = $this->program->getStakeholders($programID);

        return count($stakeholders);
    }

    public function getById2($programID)
    {
        if(empty($this->program->getTeamMemberPairs($programID)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getTeamMemberPairs($programID);
        }
    }

    public function getCount6($programID)
    {
        return count($this->program->getTeamMemberPairs($programID));
    }

    public function getById3($programID = 0)
    {
        if(empty($this->program->getTopById($programID)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getByTopId($programID);
        }
    }

    public function getTopPairs($count = '')
    {
        if($count == 'count') return count($this->program->getTopPairs());
        return $this->program->getTopPairs();
    }

    public function getUnfinished($programID)
    {
        $program = $this->program->getById($programID);

        return $this->program->hasUnfinished($program);
    }

    public function getKanbanGroup($type = ''){
        $program = $this->program->getKanbanGroup();
        return $program;
    }

    public function getListByStatus($status)
    {
        $this->program->cookie->showClosed = 'ture';
        $programs = $this->program->getList($status);
        if(!$programs) return 0;
        foreach($programs as $program)
        {
            if($program->status != $status and $status != 'all') return 0;
        }
        return count($programs);
    }

    public function getListByOrder($orderBy)
    {
        $programs = $this->program->getList('all', $orderBy);
        return checkOrder($programs, $orderBy);
    }

    public function getListByProgramID($programID = 0)
    {
        return count($this->program->getProjectList($programID));
    }

    public function getListByStatusNo($browseType = 'all')
    {
        $projects = $this->program->getProjectList('0', $browseType);

        if(!$projects) return 0;
        foreach($projects as $project)
        {
            if($project->status != $browseType and $browseType != 'all' and $browseType != 'undone') return 0;
            if($browseType == 'undone' and ($project->status != ('wait' or 'doing'))) return 0;
        }

        return count($projects);
    }

    public function getListByOrderId($orderBy = 'id_desc')
    {
        $projects = $this->program->getProjectList('0', 'all', '0', $orderBy);

        return checkOrder($projects, $orderBy);
    }

    public function getListAddProgramTitle($programTitle = 0)
    {
        return $this->program->getProjectList('0', 'all', '0', 'id_desc', '', $programTitle);
    }

    public function getListByInvolved($involved = 0, $count = '')
    {
        $projects = $this->program->getProjectList('0', 'all', '0', 'id_desc', '', '0', $involved);

        if($count == 'count') return count($projects);
        return $projects;
    }

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

    public function update($programID, $data)
    {
        global $app;

        $_POST = $data;
        $result = $this->program->update(10);
        if(dao::isError()) return array('message' => dao::getError());

        $app->dbh->query("UPDATE " . TABLE_PROGRAM . " SET name = '" . $result[0]['old']. "' where id = '" . $programID . "'");
        return $result;
    }

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
}
?>

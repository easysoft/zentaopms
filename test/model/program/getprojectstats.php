#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getProjectStats();
cid=1
pid=1

*/

class Tester
{
    public function __construct($user)
    {   
        global $tester;
        
        su($user);
        $this->program = $tester->loadModel('program');
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
}

$t = new Tester('admin');

/* GetProjectStats($programID). */
r($t->getStatsByProgramID(0)) && p() && e('68'); // 查看当前项目集下所有未开始和进行中的项目的个数

/* GetProjectStats(0, $browseType). */
r($t->getStatsByStatus('doing')) && p() && e('44'); // 查看当前项目集下所有状态为进行中的项目的个数

/* GetProjectStats(0, 'all', 0, $orderBy). */
r($t->getStatsByOrder('name_desc')) && p() && e('1'); // 根据name倒序查看所有项目
r($t->getStatsByOrder('id_desc'))   && p() && e('1'); // 根据id倒序查看所有项目的个数

/* GetProjectStats(0, 'all', 0, 'id_desc', '', $programTitle). */
r($t->getStatsAddProgramTitle(0))   && p('11:name') && e('项目1'); // 查看所有项目（包含所属项目集名称）

/* GetProjectStats(0, 'all', 0, 'id_desc', '', 0, $involved). */
r($t->getStatsByInvolved(1))   && p('11:name') && e('项目1'); // 查看当前用户参与的项目
r($t->getStatsByInvolved(1, 'count'))   && p() && e('1'); // 查看当前用户参与的项目的个数


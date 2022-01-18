#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getProjectList();
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
        $this->config->systemMode = 'new';
    }   

    public function getListByProgramID($programID = 0)
    {
        return count($this->program->getProjectList($programID));
    }

    public function getListByStatus($browseType = 'all')
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

    public function getListByOrder($orderBy = 'id_desc')
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
}

$t = new Tester('admin');

/* GetProjectList($programID). */
r($t->getListByProgramID(0)) && p() && e('90'); // 查看当前项目集下所有项目的个数

/* GetProjectList(0, $browseType). */
r($t->getListByStatus('doing')) && p() && e('44'); // 查看当前项目集下所有状态为进行中的项目的个数

/* GetProjectList(0, 'all', 0, $orderBy). */
r($t->getListByOrder('name_desc')) && p() && e('1'); // 根据name倒序查看所有项目
r($t->getListByOrder('id_desc'))   && p() && e('1'); // 根据id倒序查看所有项目的个数

/* GetProjectList(0, 'all', 0, 'id_desc', '', $programTitle). */
r($t->getListAddProgramTitle(0))   && p('11:name') && e('项目1'); // 查看所有项目（包含所属项目集名称）

/* GetProjectList(0, 'all', 0, 'id_desc', '', 0, $involved). */
r($t->getListByInvolved(1))   && p('11:name') && e('项目1'); // 查看当前用户参与的项目
r($t->getListByInvolved(1, 'count'))   && p() && e('1'); // 查看当前用户参与的项目的个数

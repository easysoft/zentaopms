#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    public function getBystatus($status)
    {
        $projects = $this->project->getOverviewList('byStatus', $status);
        if(!$projects) return 0;
        foreach($projects as $project)
        {
            if($project->status != $status) return 0;
        }
        return count($projects);
    }

    public function getListByOrder($orderBy)
    {
        $projects = $this->project->getOverviewList('byStatus', 'wait', $orderBy);
        return checkOrder($projects, $orderBy);
    }

    public function getByID($projectID)
    {
        return $this->project->getOverviewList('byID', $projectID);
    }
}

$t = new Tester('admin');

/* GetOverviewList('byStatus'). */
r($t->getByStatus('wait')) && p('') && e('15'); // 获取未开始的项目

/* GetOverviewList('byID', $id). */
r($t->getByID(11))    && p('11:id') && e('11'); // 根据项目ID获取项目详情
r($t->getByID(10000)) && p('id')    && e('');   // 获取不存在的项目

/* GetOverviewList('byStatus', 'wait', $orderBy). */
r($t->getListByOrder('id_asc'))    && p() && e('true'); // 按照ID正序获取项目列表
r($t->getListByOrder('name_desc')) && p() && e('true'); // 按照项目名称倒序获取项目列表

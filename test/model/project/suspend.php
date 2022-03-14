#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::suspend();
cid=1
pid=1

暂停id为56状态是doing的项目 >> 1
暂停id为73状态是suspended的项目 >> 0
暂停id为74状态是closed的项目 >> 0

*/
/*
class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     * Check project status after suspend a project.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     *
    public function checkStatus($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status == 'suspended' or $oldProject->status == 'closed') return false;

        $change = $this->project->suspend($projectID);

        $project = $this->project->getById($projectID);
        if($project->status != 'suspended') return false;

        return true;
    }
}*/

$t = new Project('admin');

r($t->checkStatusStop(56)) && p() && e('1'); // 暂停id为56状态是doing的项目
r($t->checkStatusStop(73)) && p() && e('0'); // 暂停id为73状态是suspended的项目
r($t->checkStatusStop(74)) && p() && e('0'); // 暂停id为74状态是closed的项目
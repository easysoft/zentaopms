#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 projectModel::suspend();
cid=1
pid=1


*/

class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     * checkStatus 
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool
     */
    public function checkStatus($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status == 'suspended' or $oldProject->status == 'closed') return false;

        $change = $this->project->suspend($projectID);
 
        $project = $this->project->getById($projectID);
        if($project->status != 'suspended') return false;

        return true;
    }
}

$t = new Tester('admin');

/* Suspend($projectID). */
r($t->checkStatus(56)) && p() && e('1'); // 暂停id为56状态是doing的项目
r($t->checkStatus(73)) && p() && e('0'); // 暂停id为73状态是suspended的项目
r($t->checkStatus(74)) && p() && e('0'); // 暂停id为74状态是closed的项目


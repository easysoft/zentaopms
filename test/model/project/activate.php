#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 projectModel::activate();
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
        if($oldProject->status != 'closed') return false;

        $change = $this->project->activate($projectID);

        $project = $this->project->getById($projectID);
        if($project->status != 'doing') return false;

        return true;
    }
}

$t = new Tester('admin');

/* Activate($projectID). */
r($t->checkStatus(66)) && p() && e('1'); //开始id为66状态不是closed的项目
r($t->checkStatus(67)) && p() && e('0'); //开始id为67状态是closed的项目

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 projectModel::close();
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

    public function checkStatus($projectID, $realEnd)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status == 'closed') return false;

        $change = $this->project->close($projectID);

        $project = $this->project->getById($projectID);
        if($project->status != 'closed') return false;

        return true;
    }
}

$t = new Tester('admin');

r($t->checkStatus(20)) && p() && e('1'); //关闭一个状态不是closed的项目
r($t->checkStatus(26)) && p() && e('0'); //关闭一个状态是closed的项目

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';


/**

title=测试 projectModel::start();
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
     * Check project status after start a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool
     */
    public function checkStatus($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status != 'suspended' and $oldProject->status != 'wait') return false;

        $change = $this->project->start($projectID);
 
        $project = $this->project->getById($projectID);
        if($project->status != 'doing') return false;

        return true;
    }
}

$t = new Tester('admin');

/* Start($projectID). */
r($t->checkStatus(81)) && p() && e('1'); // 开始id为81状态是suspended的项目
r($t->checkStatus(83)) && p() && e('1'); // 开始id为83状态是wait的项目
r($t->checkStatus(82)) && p() && e('0'); // 开始id为82状态是closed的项目
r($t->checkStatus(85)) && p() && e('0'); // 开始id为85状态是doing的项目


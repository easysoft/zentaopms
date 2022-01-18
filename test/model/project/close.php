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

    /**
     * Check project status after close a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool
     */
    public function checkStatus($projectID)
    {
        $oldProject = $this->project->getById($projectID);
        if($oldProject->status == 'suspended' or $oldProject->status == 'closed') return false;

        $change = $this->project->close($projectID);

        $project = $this->project->getById($projectID);
        if($project->status != 'closed') return false;

        return true;
    }
}

$t = new Tester('admin');

/* Close($projectID). */
r($t->checkStatus(20)) && p() && e('1'); //关闭id为20状态不是closed的项目
r($t->checkStatus(26)) && p() && e('0'); //关闭id为26状态是closed的项目
r($t->checkStatus(41)) && p() && e('0'); //关闭id为41状态是suspended的项目

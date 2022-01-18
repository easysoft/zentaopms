#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

su('admin');

/**

title=测试 projectModel::getTeamMembers();
cid=1
pid=1


*/

class Tester
{
    public function __construct($user)
    {
        global $tester;

        su('admin');
        $this->project = $tester->loadModel('project');
    }

    /**
     * getTeamMemberPairs 
     * 
     * @param  int    $projectID 
     * @access public
     * @return int
     */
    public function getTeamMemberPairs($projectID)
    {
        $members = array_filter($this->project->getTeamMemberPairs($projectID));
        if(empty($members)) return false;
        return count($members);
    }
}

$t = new Tester('admin');

/* GetTeamMemberPairs($projectID). */
r($t->getTeamMemberPairs(11)) && p() && e('2'); //获取id为11的项目团队成员个数
r($t->getTeamMemberPairs(1))  && p() && e('0'); //获取id为1的项目团队成员个数

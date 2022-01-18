#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';


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

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     * Check members. 
     * 
     * @param  array  $members 
     * @param  array  $users 
     * @access public
     * @return bool
     */
    public function checkMembers($members, $users)
    {
        foreach($users as $user)
        {
            if(!isset($members[$user])) return false;
        }
        return true;
    }

    /**
     * Get team memberm.
     * 
     * @param  int    $projectID 
     * @param  array  $users 
     * @param  bool   $tutorial 
     * @access public
     * @return int
     */
    public function getTeamMembers($projectID, $users, $tutorial = false)
    {
        if($tutorial) define('TUTORIAL', true);
        $members = $this->project->getTeamMembers($projectID);
        if(empty($members)) return false;
        if(!empty($users))  $this->checkMembers($members, $users);
        return count($members);
    }
}

$t = new Tester('admin');

/* GetTeamMembers($projectID). */
r($t->getTeamMembers(11, array('admin', 'pm92'))) && p() && e('2'); //获取id为11的项目团队成员个数
r($t->getTeamMembers(11, array('admin'), true))   && p() && e('1'); //获取id为11的项目团队成员个数，开启新手引导

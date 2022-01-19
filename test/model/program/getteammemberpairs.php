#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getTeamMemberPairs();
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
    }

    public function getById($programID)
    {
        if(empty($this->program->getTeamMemberPairs($programID)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getTeamMemberPairs($programID);
        }
    }

    public function getCount($programID)
    {
        return count($this->program->getTeamMemberPairs($programID));
    }
}

$t = new Tester('admin');

/* GetTeamMemberPairs($programID). */
r($t->getById(1)) && p('user89') && e('U:测试89'); //获取项目集下所有团队成员
r($t->getCount(1)) && p() && e('181'); // 获取项目集下所有团队成员

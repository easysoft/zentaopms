#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('dev1');
/**

title=测试 programModel::getTopByID();
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

    public function getById($programID = 0)
    {
        if(empty($this->program->getTopById($programID)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getByTopId($programID);
        }
    }
}

$t = new Tester('admin');

/* GetTopById($programID). */
r($t->getByID(1)) && p('message') && e('Not Found'); // 获取项目集1最上级的项目集id

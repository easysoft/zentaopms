#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->program = $tester->loadModel('program');
    }

    public function getChildren($programID)
    {
        $programInfo = $this->program->getChildren($programID);
        if(empty($programInfo))
        {
            return array('code' => 'fail' , 'message' => 'Not Found');
        }
        else
        {
            return $programInfo;
        }
    }
}

$t = new Tester('admin');

/**

title=测试 programModel:: getChildren();
cid=1
pid=1

*/

r($t->getChildren(1))   && p()          && e('9'); // 通过id查找id=1的子项目集个数
r($t->getChildren(220)) && p()          && e('5'); // 通过id查找id=220的子项目集个数
r($t->getChildren(221)) && p('message') && e('Not Found'); // 通过id查找id=221的子项目集个数

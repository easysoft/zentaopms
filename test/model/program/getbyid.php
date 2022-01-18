#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

class Tester
{
    public function __construct($user)
    {
        global $tester;

        su('admin');
        $this->program = $tester->loadModel('program');
    }

    public function getById($programID)
    {
        if(empty($this->program->getById($programID)))
        {
            return array('code' => 'fail', 'message' => 'Not Found');
        }
        else
        {
            return $this->program->getById($programID);
        }
    }
}

$t = new Tester('admin');

/**

title=测试 programModel::getById();
cid=1
pid=1

*/

r($t->getById(1))    && p('name')    && e('项目集1'); // 通过id字段获取id=1的项目集并验证它的name。
r($t->getById(1000)) && p('message') && e('Not Found'); // 通过id字段获取id=1000的项目集并验证它的name。

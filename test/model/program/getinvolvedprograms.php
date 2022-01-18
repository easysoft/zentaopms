#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getInvolvedPrograms();
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

    public function getInvolvedPrograms($account)
    {
        return $this->program->getInvolvedPrograms($account);
    }
}

$t = new Tester('admin');

/* GetInvolvedPrograms($account). */
r($t->getInvolvedPrograms('admin')) && p('122') && e('122'); // 查看用户admin可以看到的项目和执行id列表
r($t->getInvolvedPrograms('test2')) && p('1;122') && e('1;122'); // 查看用户test2可以看到的项目和执行id列表

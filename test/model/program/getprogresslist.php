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

    public function getProgressList()
    {
        return $this->program->getProgressList();
    }

    public function getCount()
    {
        return count($this->program->getProgressList());
    }
}

$t = new Tester('admin');

/**

title=测试 programModee::getProgressList();
cid=1
pid=1

*/

r($t->getCount())        && p()     && e('100'); // 获取项目和项目集的个数
r($t->getProgressList()) && p('1')  && e('0'); // 获取id=1的项目的进度
r($t->getProgressList()) && p('11') && e('0'); // 获取id=11的项目集的进度

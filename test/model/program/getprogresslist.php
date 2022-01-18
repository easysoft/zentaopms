#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModee::getProgressList();
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

/* Count(). */
r($t->getCount())        && p()     && e('100'); // 获取项目和项目集的个数

/* GetProgressList(). */
r($t->getProgressList()) && p('1')  && e('0'); // 获取id=1的项目的进度
r($t->getProgressList()) && p('11') && e('0'); // 获取id=11的项目集的进度

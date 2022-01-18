#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::hasUnfinished();
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

    public function getUnfinished($programID)
    {
        $program = $this->program->getById($programID);

        return $this->program->hasUnfinished($program);
    }
}

$t = new Tester('admin');

/* HasUnfinished($program). */
r($t->getUnfinished(1)) && p() && e('88'); // 获取项目集1下未完成的项目和项目集

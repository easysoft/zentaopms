#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getBudgetLeft();
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

    public function getBudgetLeft($programID)
    {
        $program = $this->program->getById(1);

        return $this->program->getBudgetLeft($program);
    }
}

$t = new Tester('admin');

/* GetBudgetLeft($program). */
r($t->getBudgetLeft(1)) && p() && e('0'); // 查看父项目集id=1的预算剩余

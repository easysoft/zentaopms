#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getBudgetUnitList();
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

    public function getBudgetUnitList()
    {
        global $app;
        $app->loadConfig('project');
        $app->loadLang('project');

        return $this->program->getBudgetUnitList();
    }
}

$t = new Tester('admin');

/* GetBudgetUnitList(). */
r($t->getBudgetUnitList()) && p('CNY;USD') && e('人民币;美元'); //获取货币类型列表

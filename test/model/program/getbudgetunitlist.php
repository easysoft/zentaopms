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

    public function getBudgetUnitList()
    {
        global $app;
        $app->loadConfig('project');
        $app->loadLang('project');

        return $this->program->getBudgetUnitList();
    }
}

$t = new Tester('admin');

/**

title=测试 programModel::getBudgetUnitList();
cid=1
pid=1

*/

r($t->getBudgetUnitList()) && p('CNY;USD') && e('人民币;美元'); //获取货币类型列表

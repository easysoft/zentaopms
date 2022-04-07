#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getBudgetUnitList();
cid=1
pid=1

检查翻译 >> 1

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getBudgetUnitList()) && p('CNY,USD') && e('人民币,美元'); //获取系统中的货币单位

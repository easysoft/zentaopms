#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 projectModel::getBudgetUnitList();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');
$tester->config->project->unitList = 'CNY,USD';

r($tester->project->getBudgetUnitList()) && p('CNY,USD') && e('人民币,美元');

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 projectModel::getBudgetUnitList();
cid=1

- 执行project模块的getBudgetUnitList方法
 - 属性CNY @¥ 人民币
 - 属性USD @$ 美元

*/

global $tester;
$tester->loadModel('project');
$tester->config->project->unitList = 'CNY,USD';

r($tester->project->getBudgetUnitList()) && p('CNY,USD') && e('¥ 人民币,$ 美元');

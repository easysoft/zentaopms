#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getBudgetUnitList();
cid=1
pid=1

获取货币类型列表 >> 人民币;美元

*/

global $tester;
$tester->loadModel('project');

r($tester->loadModel('program')->getBudgetUnitList()) && p('CNY;USD') && e('人民币;美元'); //获取货币类型列表
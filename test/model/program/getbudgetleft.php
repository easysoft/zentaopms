#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getBudgetLeft();
cid=1
pid=1

查看项目11的所有父项目集的预算剩余 >> 900000
查看项目12的所有父项目集的预算剩余 >> 899900

*/

global $tester;
$tester->loadModel('program');
$program1 = $tester->program->getById(11);
$program2 = $tester->program->getById(12);
$result1  = $tester->program->getBudgetLeft($program1);
$result2  = $tester->program->getBudgetLeft($program2);

r($result) && p() && e('900000');  // 查看项目11的所有父项目集的预算剩余
r($result) && p() && e('899900');  // 查看项目12的所有父项目集的预算剩余
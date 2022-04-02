#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getBudgetLeft();
cid=1
pid=1

查看父项目集id=1的预算剩余 >> 0

*/

global $tester;
$tester->loadModel('program');
$program1 = $tester->program->getById(11);
$program2 = $tester->program->getById(12);
$result1  = $tester->program->getBudgetLeft($program1);
$result2  = $tester->program->getBudgetLeft($program2);

r($result) && p() && e('0');  // 查看父项目集id=1的预算剩余

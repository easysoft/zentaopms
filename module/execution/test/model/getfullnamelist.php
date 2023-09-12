#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('execution')->gen(30);

/**

title=测试 executionModel->getFullNameList();
timeout=0
cid=1

*/

global $tester;
$executionModel = $tester->loadModel('execution');
$allExecutions  = $executionModel->fetchExecutionList();

r($executionModel->getFullNameList(array()))               && p()      && e('0');     // 空执行的情况
r($executionModel->getFullNameList($allExecutions))        && p('101') && e('迭代5'); // 正常情况
r(count($executionModel->getFullNameList($allExecutions))) && p()      && e(20);      // 正常情况

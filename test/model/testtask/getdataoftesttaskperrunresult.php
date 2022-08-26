#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 testtaskModel->getDataOfTestTaskPerRunResult();
cid=1
pid=1

获取测试单0的执行结果 >> 0
获取测试单1的成功执行结果 >> 2
获取测试单1的失败执行结果 >> 2
获取测试单2的成功执行结果 >> 2
获取测试单2的失败执行结果 >> 2

*/

global $tester;
$tester->loadModel('testtask');

$result0 = $tester->testtask->getDataOfTestTaskPerRunResult(0);
$result1 = $tester->testtask->getDataOfTestTaskPerRunResult(1);
$result2 = $tester->testtask->getDataOfTestTaskPerRunResult(2);

r($result0) && p()             && e('0'); // 获取测试单0的执行结果
r($result1) && p('pass:value') && e('2'); // 获取测试单1的成功执行结果
r($result1) && p('fail:value') && e('2'); // 获取测试单1的失败执行结果
r($result2) && p('pass:value') && e('2'); // 获取测试单2的成功执行结果
r($result2) && p('fail:value') && e('2'); // 获取测试单2的失败执行结果
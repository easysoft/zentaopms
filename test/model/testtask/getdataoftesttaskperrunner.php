#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->getDataOfTestTaskPerRunner();
cid=1
pid=1

获取测试单0的执行人分组 >> 0
获取测试单1执行人分组，查看各个执行人的执行数量 >> 未执行,4
获取测试单2执行人分组，查看各个执行人的执行数量 >> 未执行,4

*/

global $tester;
$tester->loadModel('testtask');

$result0 = $tester->testtask->getDataOfTestTaskPerRunner(0);
$result1 = $tester->testtask->getDataOfTestTaskPerRunner(1);
$result2 = $tester->testtask->getDataOfTestTaskPerRunner(2);

r($result0)          && p()             && e('0');        // 获取测试单0的执行人分组
r(current($result1)) && p('name,value') && e('未执行,4'); // 获取测试单1执行人分组，查看各个执行人的执行数量
r(current($result2)) && p('name,value') && e('未执行,4'); // 获取测试单2执行人分组，查看各个执行人的执行数量
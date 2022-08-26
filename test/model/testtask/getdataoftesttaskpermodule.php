#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->getDataOfTestTaskPerModule();
cid=1
pid=1

获取测试单0的模块分组 >> 0
获取测试单1模块分组，查看各个模块的数量 >> /,4
获取测试单2模块分组，查看各个模块的数量 >> /,4

*/

global $tester;
$tester->loadModel('testtask');

$result0 = $tester->testtask->getDataOfTestTaskPerModule(0);
$result1 = $tester->testtask->getDataOfTestTaskPerModule(1);
$result2 = $tester->testtask->getDataOfTestTaskPerModule(2);

r($result0)    && p()             && e('0');   // 获取测试单0的模块分组
r($result1[0]) && p('name,value') && e('/,4'); // 获取测试单1模块分组，查看各个模块的数量
r($result2[0]) && p('name,value') && e('/,4'); // 获取测试单2模块分组，查看各个模块的数量
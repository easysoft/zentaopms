#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->getDataOfTestTaskPerType();
cid=1
pid=1

获取测试单0的类型分组 >> 0
获取测试单1的config类型数量 >> 1
获取测试单1的feature类型数量 >> 1
获取测试单1的install类型数量 >> 1
获取测试单1的performance类型数量 >> 1
获取测试单2的other类型数量 >> 1
获取测试单2的security类型数量 >> 1
获取测试单2的feature类型数量 >> 1
获取测试单2的interface类型数量 >> 1

*/

global $tester;
$tester->loadModel('testtask');

$result0 = $tester->testtask->getDataOfTestTaskPerType(0);
$result1 = $tester->testtask->getDataOfTestTaskPerType(1);
$result2 = $tester->testtask->getDataOfTestTaskPerType(2);

r($result0) && p()                    && e('0'); // 获取测试单0的类型分组
r($result1) && p('config:value')      && e('1'); // 获取测试单1的config类型数量
r($result1) && p('feature:value')     && e('1'); // 获取测试单1的feature类型数量
r($result1) && p('install:value')     && e('1'); // 获取测试单1的install类型数量
r($result1) && p('performance:value') && e('1'); // 获取测试单1的performance类型数量
r($result2) && p('other:value')       && e('1'); // 获取测试单2的other类型数量
r($result2) && p('security:value')    && e('1'); // 获取测试单2的security类型数量
r($result2) && p('feature:value')     && e('1'); // 获取测试单2的feature类型数量
r($result2) && p('interface:value')   && e('1'); // 获取测试单2的interface类型数量
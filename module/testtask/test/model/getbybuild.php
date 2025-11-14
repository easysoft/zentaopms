#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('testtask')->gen(5); // 前 5 条测试单的 build 字段值分别为 11, 12, 13, 14, 15。
su('admin');

/**

title=测试 testtaskModel->getByBuild();
timeout=0
cid=19164

*/

global $tester;
$testtask = $tester->loadModel('testtask');

r($testtask->getByBuild(0))  && p('') && e(0); // 获取 ID 为 0 的测试单，返回 false。
r($testtask->getByBuild(16)) && p('') && e(0); // 获取 ID 为 16 的测试单，返回 false。
r($testtask->getByBuild(11)) && p('project,name,product,execution,build,owner,desc,status,auto') && e('11,测试单1,1,101,11,user3,这是测试单描述1,wait,no');    // 获取 ID 为 1 的测试单的详细信息。
r($testtask->getByBuild(12)) && p('project,name,product,execution,build,owner,desc,status,auto') && e('12,测试单2,2,102,12,user4,这是测试单描述2,doing,no');   // 获取 ID 为 2 的测试单的详细信息。
r($testtask->getByBuild(13)) && p('project,name,product,execution,build,owner,desc,status,auto') && e('13,测试单3,3,103,13,user5,这是测试单描述3,done,no');    // 获取 ID 为 3 的测试单的详细信息。
r($testtask->getByBuild(14)) && p('project,name,product,execution,build,owner,desc,status,auto') && e('14,测试单4,4,104,14,user6,这是测试单描述4,blocked,no'); // 获取 ID 为 4 的测试单的详细信息。
r($testtask->getByBuild(15)) && p('project,name,product,execution,build,owner,desc,status,auto') && e('15,测试单5,5,105,15,user7,这是测试单描述5,wait,no');    // 获取 ID 为 5 的测试单的详细信息。

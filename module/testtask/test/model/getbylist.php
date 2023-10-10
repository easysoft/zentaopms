#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('testtask')->gen(5);

/**

title=测试 testtaskModel->getByList();
cid=1
pid=1

*/

global $tester;
$testtask = $tester->loadModel('testtask');

r($testtask->getByList(array())) && p() && e(0); // idList 参数为空，返回 false。

$tasks = $testtask->getByList(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));

r(count($tasks)) && p() && e(5); // idList 参数包含 10 个 ID，数据库里只有 5 条数据，只返回 5 条数据。

r($tasks) && p('1:project,name,product,execution,build,owner,desc,status,auto') && e('11,测试单1,1,101,11,user3,这是测试单描述1,wait,no');    // 获取 ID 为 1 的测试单的详细信息。
r($tasks) && p('2:project,name,product,execution,build,owner,desc,status,auto') && e('12,测试单2,2,102,12,user4,这是测试单描述2,doing,no');   // 获取 ID 为 2 的测试单的详细信息。
r($tasks) && p('3:project,name,product,execution,build,owner,desc,status,auto') && e('13,测试单3,3,103,13,user5,这是测试单描述3,done,no');    // 获取 ID 为 3 的测试单的详细信息。
r($tasks) && p('4:project,name,product,execution,build,owner,desc,status,auto') && e('14,测试单4,4,104,14,user6,这是测试单描述4,blocked,no'); // 获取 ID 为 4 的测试单的详细信息。
r($tasks) && p('5:project,name,product,execution,build,owner,desc,status,auto') && e('15,测试单5,5,105,15,user7,这是测试单描述5,wait,no');    // 获取 ID 为 5 的测试单的详细信息。

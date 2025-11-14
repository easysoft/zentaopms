#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('testtask')->gen(5);

/**

title=测试 testtaskModel->getByList();
timeout=0
cid=19166

- idList 参数为空，返回 false。 @0
- idList 参数包含 10 个 ID，数据库里只有 5 条数据，只返回 5 条数据。 @5
- 获取 ID 为 1 的测试单的详细信息。
 - 第1条的project属性 @11
 - 第1条的name属性 @测试单1
 - 第1条的product属性 @1
 - 第1条的execution属性 @101
 - 第1条的build属性 @11
 - 第1条的owner属性 @user3
 - 第1条的desc属性 @这是测试单描述1
 - 第1条的status属性 @wait
 - 第1条的auto属性 @no
- 获取 ID 为 2 的测试单的详细信息。
 - 第2条的project属性 @12
 - 第2条的name属性 @测试单2
 - 第2条的product属性 @2
 - 第2条的execution属性 @102
 - 第2条的build属性 @12
 - 第2条的owner属性 @user4
 - 第2条的desc属性 @这是测试单描述2
 - 第2条的status属性 @doing
 - 第2条的auto属性 @no
- 获取 ID 为 3 的测试单的详细信息。
 - 第3条的project属性 @13
 - 第3条的name属性 @测试单3
 - 第3条的product属性 @3
 - 第3条的execution属性 @103
 - 第3条的build属性 @13
 - 第3条的owner属性 @user5
 - 第3条的desc属性 @这是测试单描述3
 - 第3条的status属性 @done
 - 第3条的auto属性 @no
- 获取 ID 为 4 的测试单的详细信息。
 - 第4条的project属性 @14
 - 第4条的name属性 @测试单4
 - 第4条的product属性 @4
 - 第4条的execution属性 @104
 - 第4条的build属性 @14
 - 第4条的owner属性 @user6
 - 第4条的desc属性 @这是测试单描述4
 - 第4条的status属性 @blocked
 - 第4条的auto属性 @no
- 获取 ID 为 5 的测试单的详细信息。
 - 第5条的project属性 @15
 - 第5条的name属性 @测试单5
 - 第5条的product属性 @5
 - 第5条的execution属性 @105
 - 第5条的build属性 @15
 - 第5条的owner属性 @user7
 - 第5条的desc属性 @这是测试单描述5
 - 第5条的status属性 @wait
 - 第5条的auto属性 @no

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

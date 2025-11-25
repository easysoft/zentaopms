#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 testtaskModel->getToAndCcList();
timeout=0
cid=0

- 测试单对象无任何属性，查看返回值。 @0
- 测试单对象只有一个负责人属性，其值为空，查看返回值。 @0
- 测试单对象只有一个抄送给属性，其值为空，查看返回值。 @0
- 测试单对象同时有负责人属性和抄送给属性，其值都为空，查看返回值。 @0
- 测试单对象只有一个负责人属性，其值不为空，查看返回值。
 -  @user1
 - 属性1 @~~
- 测试单对象只有一个抄送给属性，其值不为空且不包含逗号，查看返回值。
 -  @user2
 - 属性1 @~~
- 测试单对象只有一个抄送给属性，其值不为空且包含逗号，逗号之后无内容，查看返回值。
 -  @user2
 - 属性1 @~~
- 测试单对象只有一个抄送给属性，其值不为空且包含逗号，逗号之前无内容，查看返回值。
 -  @user3
 - 属性1 @~~
- 测试单对象只有一个抄送给属性，其值不为空且包含逗号，逗号前后都有内容，查看返回值。
 -  @user2
 - 属性1 @user3
- 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性不包含逗号，查看返回值。
 -  @user1
 - 属性1 @user2
- 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性包含逗号，逗号后无内容，查看返回值。
 -  @user1
 - 属性1 @user2
- 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性包含逗号，逗号前无内容，查看返回值。
 -  @user1
 - 属性1 @user3
- 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性包含逗号，逗号前后都有内容，查看返回值。
 -  @user1
 - 属性1 @user2,user3

*/

global $tester;
$testtask = $tester->loadModel('testtask');

$task1  = new stdclass();
$task2  = (object)array('owner' => '');
$task3  = (object)array('mailto' => '');
$task4  = (object)array('owner' => '', 'mailto' => '');
$task5  = (object)array('owner' => 'user1');
$task6  = (object)array('mailto' => 'user2');
$task7  = (object)array('mailto' => 'user2,');
$task8  = (object)array('mailto' => ',user3');
$task9  = (object)array('mailto' => 'user2,user3');
$task10 = (object)array('owner' => 'user1', 'mailto' => 'user2');
$task11 = (object)array('owner' => 'user1', 'mailto' => 'user2,');
$task12 = (object)array('owner' => 'user1', 'mailto' => ',user3');
$task13 = (object)array('owner' => 'user1', 'mailto' => 'user2,user3');

r($testtask->getToAndCcList($task1)) && p() && e(0); // 测试单对象无任何属性，查看返回值。
r($testtask->getToAndCcList($task2)) && p() && e(0); // 测试单对象只有一个负责人属性，其值为空，查看返回值。
r($testtask->getToAndCcList($task3)) && p() && e(0); // 测试单对象只有一个抄送给属性，其值为空，查看返回值。
r($testtask->getToAndCcList($task4)) && p() && e(0); // 测试单对象同时有负责人属性和抄送给属性，其值都为空，查看返回值。

r($testtask->getToAndCcList($task5))  && p('0,1')      && e('user1,~~');          // 测试单对象只有一个负责人属性，其值不为空，查看返回值。
r($testtask->getToAndCcList($task6))  && p('0,1')      && e('user2,~~');          // 测试单对象只有一个抄送给属性，其值不为空且不包含逗号，查看返回值。
r($testtask->getToAndCcList($task7))  && p('0,1')      && e('user2,~~');          // 测试单对象只有一个抄送给属性，其值不为空且包含逗号，逗号之后无内容，查看返回值。
r($testtask->getToAndCcList($task8))  && p('0,1')      && e('user3,~~');          // 测试单对象只有一个抄送给属性，其值不为空且包含逗号，逗号之前无内容，查看返回值。
r($testtask->getToAndCcList($task9))  && p('0,1')      && e('user2,user3');       // 测试单对象只有一个抄送给属性，其值不为空且包含逗号，逗号前后都有内容，查看返回值。
r($testtask->getToAndCcList($task10)) && p('0,1')      && e('user1,user2');       // 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性不包含逗号，查看返回值。
r($testtask->getToAndCcList($task11)) && p('0,1')      && e('user1,user2');       // 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性包含逗号，逗号后无内容，查看返回值。
r($testtask->getToAndCcList($task12)) && p('0,1')      && e('user1,user3');       // 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性包含逗号，逗号前无内容，查看返回值。
r($testtask->getToAndCcList($task13)) && p('0|1', '|') && e('user1|user2,user3'); // 测试单对象同时有负责人属性和抄送给属性，其值都不为空，抄送给属性包含逗号，逗号前后都有内容，查看返回值。

#!/usr/bin/env php
<?php
/**

title=测试 executionZen::assignViewVars();
timeout=0
cid=16410

- 获取产品数据
 - 第5条的name属性 @正常产品5
 - 第5条的type属性 @normal
 - 第5条的status属性 @normal
- 获取用户数据
 - 属性admin @admin
 - 属性user1 @用户1
 - 属性user2 @用户2
 - 属性user3 @用户3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->loadYaml('execution')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);
zenData('product')->gen(10);
zenData('branch')->gen(0);
zenData('doc')->gen(0);
zenData('user')->gen(5);
su('admin');

$executionTester = new executionZenTest();
$result = $executionTester->assignViewVarsTest(101);
r($result->products) && p('5:name,type,status')      && e('正常产品5,normal,normal'); // 获取产品数据
r($result->users)    && p('admin,user1,user2,user3') && e('admin,用户1,用户2,用户3'); // 获取用户数据

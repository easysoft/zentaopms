#!/usr/bin/env php
<?php
/**

title=测试 projectZen::buildEditForm();
timeout=0
cid=17928

- 测试获取编辑的表单数据 @编辑项目
- 测试获取编辑的表单数据
 - 属性admin @A:admin
 - 属性user1 @U:用户1
 - 属性user2 @U:用户2
- 测试获取编辑的表单数据
 - 属性1 @/产品1
 - 属性2 @/产品2
 - 属性3 @/产品3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';
zenData('project')->loadYaml('execution')->gen(5);
zenData('product')->loadYaml('product')->gen(5);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);
zenData('branch')->gen(0);
zenData('user')->gen(5);
su('admin');

$projectTester = new projectZenTest();

$result = $projectTester->buildEditFormTest(11);
r($result->title)       && p()                    && e('编辑项目');                // 测试获取编辑的表单数据
r($result->users)       && p('admin,user1,user2') && e('A:admin,U:用户1,U:用户2'); // 测试获取编辑的表单数据
r($result->allProducts) && p('1,2,3')             && e('/产品1,/产品2,/产品3');    // 测试获取编辑的表单数据

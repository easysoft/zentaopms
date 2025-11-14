#!/usr/bin/env php
<?php
/**

title=测试 weeklyModel->addBuiltinScope();
timeout=0
cid=19715

- 测试添加内置范围
 - 属性name @项目
 - 属性type @reportTemplate
 - 属性main @1
 - 属性addedBy @system
- 测试重复添加内置范围属性id @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';

zenData('doclib')->gen(0);
zenData('user')->gen(5);
su('admin');

$weeklyTester = new weeklyTest();
r($weeklyTester->addBuiltinScopeTest()) && p('name,type,main,addedBy') && e('项目,reportTemplate,1,system'); // 测试添加内置范围
r($weeklyTester->addBuiltinScopeTest()) && p('id')                     && e('1');                            // 测试重复添加内置范围

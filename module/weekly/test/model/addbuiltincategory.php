#!/usr/bin/env php
<?php
/**

title=测试 weeklyModel->addBuiltinCategory();
timeout=0
cid=19714

- 测试添加内置分类
 - 属性id @1
 - 属性root @1
 - 属性name @项目
 - 属性grade @1
 - 属性type @reportTemplate

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';

zenData('doclib')->gen(0);
zenData('module')->gen(0);
zenData('user')->gen(5);
su('admin');

$weeklyTester = new weeklyTest();
r($weeklyTester->addBuiltinCategoryTest()) && p('id,root,name,grade,type') && e('1,1,项目,1,reportTemplate'); // 测试添加内置分类

#!/usr/bin/env php
<?php
/**

title=测试 weeklyModel::addBuiltinWeeklyTemplate();
timeout=0
cid=19717

- 添加内置报告模块数据
 - 属性templateType @reportTemplate
 - 属性cycle @week
 - 属性title @项目周报模板
 - 属性status @normal

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
zenData('doclib')->gen(0);
zenData('module')->gen(0);
zenData('user')->gen(5);
su('admin');

$weeklyTester = new weeklyTest();
r($weeklyTester->addBuiltinWeeklyTemplateTest()) && p('templateType,cycle,title,status') && e('reportTemplate,week,项目周报模板,normal'); // 添加内置报告模块数据

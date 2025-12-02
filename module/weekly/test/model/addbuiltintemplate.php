#!/usr/bin/env php
<?php
/**

title=测试 weeklyModel->addBuiltinTemplate();
timeout=0
cid=19716

- 测试添加内置模板
 - 属性title @项目周报模板
 - 属性type @text
 - 属性status @normal
 - 属性acl @open
 - 属性builtIn @1
 - 属性templateType @reportTemplate
 - 属性cycle @week
 - 属性addedBy @system

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';

zenData('action')->gen(0);
zenData('doclib')->gen(0);
zenData('module')->gen(0);
zenData('workflowgroup')->gen(0);
zenData('doc')->gen(0);
zenData('doccontent')->gen(0);
zenData('user')->gen(5);
su('admin');

$weeklyTester = new weeklyTest();
r($weeklyTester->addBuiltinTemplateTest()) && p('title,type,status,acl,builtIn,templateType,cycle,addedBy') && e('项目周报模板,text,normal,open,1,reportTemplate,week,system'); // 测试添加内置模板

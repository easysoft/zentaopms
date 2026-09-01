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
- 再次添加验证模板类型 @text
- 验证内置标记 @1
- 验证访问控制 @open
- 验证创建人 @system

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('doclib')->gen(0);
zenData('module')->gen(0);
zenData('user')->gen(5);
su('admin');

$weeklyTester = new weeklyModelTest();
r($weeklyTester->addBuiltinWeeklyTemplateTest()) && p('templateType,cycle,title,status') && e('reportTemplate,week,项目周报模板,normal'); // 添加内置报告模块数据
r($weeklyTester->addBuiltinWeeklyTemplateTest()) && p('type') && e('text'); // 再次添加验证模板类型
r($weeklyTester->addBuiltinWeeklyTemplateTest()) && p('builtIn') && e('1'); // 验证内置标记
r($weeklyTester->addBuiltinWeeklyTemplateTest()) && p('acl') && e('open'); // 验证访问控制
r($weeklyTester->addBuiltinWeeklyTemplateTest()) && p('addedBy') && e('system'); // 验证创建人

#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addBuiltInDocTemplate();
timeout=0
cid=1

- 获取内置项目计划模板 @1
- 获取内置软件需求说明书模板 @1
- 获取内置概要设计说明书模板 @1
- 获取内置详细设计说明书模板 @1
- 获取内置接口设计文档模板 @1
- 获取内置数据库设计文档模板 @1
- 获取内置集成测试用例模板 @1
- 获取内置系统测试用例模板 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('user')->loadYaml('user')->gen(5);
su('admin');

zenData('doc')->gen(0);
zenData('doccontent')->gen(0);
zenData('module')->gen(0);
zenData('doclib')->gen(0);
zenData('lang')->gen(0);
$upgrade = new upgradeTest();
r($upgrade->addBuiltInDocTemplateTest(1, '项目计划'))           && p() && e('1'); // 获取内置项目计划模板
r($upgrade->addBuiltInDocTemplateTest(2, '软件需求规格说明书')) && p() && e('1'); // 获取内置软件需求说明书模板
r($upgrade->addBuiltInDocTemplateTest(3, '概要设计说明书'))     && p() && e('1'); // 获取内置概要设计说明书模板
r($upgrade->addBuiltInDocTemplateTest(4, '详细设计说明书'))     && p() && e('1'); // 获取内置详细设计说明书模板
r($upgrade->addBuiltInDocTemplateTest(5, '接口设计文档'))       && p() && e('1'); // 获取内置接口设计文档模板
r($upgrade->addBuiltInDocTemplateTest(6, '数据库设计文档'))     && p() && e('1'); // 获取内置数据库设计文档模板
r($upgrade->addBuiltInDocTemplateTest(7, '集成测试用例'))       && p() && e('1'); // 获取内置集成测试用例模板
r($upgrade->addBuiltInDocTemplateTest(8, '系统测试用例'))       && p() && e('1'); // 获取内置系统测试用例模板

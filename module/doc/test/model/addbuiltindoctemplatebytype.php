#!/usr/bin/env php
<?php

/**

title=测试 docModel->addBuiltInDocTemplateByType();
timeout=0
cid=16038

- 添加内置项目计划模板 @1
- 添加内置软件需求说明书模板 @1
- 添加内置概要设计说明书模板 @1
- 添加内置详细设计说明书模板 @1
- 添加内置接口设计文档模板 @1
- 添加内置数据库设计文档模板 @1
- 添加内置集成测试用例模板 @1
- 添加内置系统测试用例模板 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('doc')->gen(0);
zenData('doccontent')->gen(0);
zenData('doclib')->gen(0);
zenData('module')->gen(0);
zenData('lang')->gen(0);
$docTester = new docTest();
r($docTester->addBuiltInDocTemplateByTypeTest(1, array('PP'),   '项目计划'))           && p() && e('1'); // 添加内置项目计划模板
r($docTester->addBuiltInDocTemplateByTypeTest(2, array('SRS'),  '软件需求规格说明书')) && p() && e('1'); // 添加内置软件需求说明书模板
r($docTester->addBuiltInDocTemplateByTypeTest(3, array('HLDS'), '概要设计说明书'))     && p() && e('1'); // 添加内置概要设计说明书模板
r($docTester->addBuiltInDocTemplateByTypeTest(4, array('DDS'),  '详细设计说明书'))     && p() && e('1'); // 添加内置详细设计说明书模板
r($docTester->addBuiltInDocTemplateByTypeTest(5, array('ADS'),  '接口设计文档'))       && p() && e('1'); // 添加内置接口设计文档模板
r($docTester->addBuiltInDocTemplateByTypeTest(6, array('DBDS'), '数据库设计文档'))     && p() && e('1'); // 添加内置数据库设计文档模板
r($docTester->addBuiltInDocTemplateByTypeTest(7, array('ITTC'), '集成测试用例'))       && p() && e('1'); // 添加内置集成测试用例模板
r($docTester->addBuiltInDocTemplateByTypeTest(8, array('STTC'), '系统测试用例'))       && p() && e('1'); // 添加内置系统测试用例模板

#!/usr/bin/env php
<?php

/**

title=测试 docModel->addBuiltInDocTemplateByType();
timeout=0
cid=1

- 添加内置项目计划模板
 - 属性templateType @PP
 - 属性title @项目计划
- 添加内置软件需求说明书模板
 - 属性templateType @SRS
 - 属性title @软件需求规格说明书
- 添加内置概要设计说明书模板
 - 属性templateType @HLDS
 - 属性title @概要设计说明书
- 添加内置详细设计说明书模板
 - 属性templateType @DDS
 - 属性title @详细设计说明书
- 添加内置接口设计文档模板
 - 属性templateType @ADS
 - 属性title @接口设计文档
- 添加内置数据库设计文档模板
 - 属性templateType @DBDS
 - 属性title @数据库设计文档
- 添加内置集成测试用例模板
 - 属性templateType @ITTC
 - 属性title @集成测试用例
- 添加内置系统测试用例模板
 - 属性templateType @STTC
 - 属性title @系统测试用例

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('doc')->gen(0);
zenData('doccontent')->gen(0);
zenData('doclib')->gen(0);
zenData('module')->gen(0);
$docTester = new docTest();
r($docTester->addBuiltInDocTemplateByTypeTest(1, array('PP')))   && p('templateType,title') && e('PP,项目计划');            // 添加内置项目计划模板
r($docTester->addBuiltInDocTemplateByTypeTest(2, array('SRS')))  && p('templateType,title') && e('SRS,软件需求规格说明书'); // 添加内置软件需求说明书模板
r($docTester->addBuiltInDocTemplateByTypeTest(3, array('HLDS'))) && p('templateType,title') && e('HLDS,概要设计说明书');    // 添加内置概要设计说明书模板
r($docTester->addBuiltInDocTemplateByTypeTest(4, array('DDS')))  && p('templateType,title') && e('DDS,详细设计说明书');     // 添加内置详细设计说明书模板
r($docTester->addBuiltInDocTemplateByTypeTest(5, array('ADS')))  && p('templateType,title') && e('ADS,接口设计文档');       // 添加内置接口设计文档模板
r($docTester->addBuiltInDocTemplateByTypeTest(6, array('DBDS'))) && p('templateType,title') && e('DBDS,数据库设计文档');    // 添加内置数据库设计文档模板
r($docTester->addBuiltInDocTemplateByTypeTest(7, array('ITTC'))) && p('templateType,title') && e('ITTC,集成测试用例');      // 添加内置集成测试用例模板
r($docTester->addBuiltInDocTemplateByTypeTest(8, array('STTC'))) && p('templateType,title') && e('STTC,系统测试用例');      // 添加内置系统测试用例模板

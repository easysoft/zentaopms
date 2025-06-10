#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addBuiltInDocTemplate();
timeout=0
cid=1

- 获取内置项目计划模板
 - 属性templateType @PP
 - 属性title @项目计划
- 获取内置软件需求说明书模板
 - 属性templateType @SRS
 - 属性title @软件需求规格说明书
- 获取内置概要设计说明书模板
 - 属性templateType @HLDS
 - 属性title @概要设计说明书
- 获取内置详细设计说明书模板
 - 属性templateType @DDS
 - 属性title @详细设计说明书
- 获取内置接口设计文档模板
 - 属性templateType @ADS
 - 属性title @接口设计文档
- 获取内置数据库设计文档模板
 - 属性templateType @DBDS
 - 属性title @数据库设计文档
- 获取内置集成测试用例模板
 - 属性templateType @ITTC
 - 属性title @集成测试用例
- 获取内置系统测试用例模板
 - 属性templateType @STTC
 - 属性title @系统测试用例

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('user')->loadYaml('user')->gen(5);
su('admin');

zenData('doc')->gen(0);
zenData('doccontent')->gen(0);
zenData('module')->gen(0);
zenData('doclib')->gen(0);
$upgrade = new upgradeTest();
r($upgrade->addBuiltInDocTemplateTest(1)) && p('templateType,title') && e('PP,项目计划');            // 获取内置项目计划模板
r($upgrade->addBuiltInDocTemplateTest(2)) && p('templateType,title') && e('SRS,软件需求规格说明书'); // 获取内置软件需求说明书模板
r($upgrade->addBuiltInDocTemplateTest(3)) && p('templateType,title') && e('HLDS,概要设计说明书');    // 获取内置概要设计说明书模板
r($upgrade->addBuiltInDocTemplateTest(4)) && p('templateType,title') && e('DDS,详细设计说明书');     // 获取内置详细设计说明书模板
r($upgrade->addBuiltInDocTemplateTest(5)) && p('templateType,title') && e('ADS,接口设计文档');       // 获取内置接口设计文档模板
r($upgrade->addBuiltInDocTemplateTest(6)) && p('templateType,title') && e('DBDS,数据库设计文档');    // 获取内置数据库设计文档模板
r($upgrade->addBuiltInDocTemplateTest(7)) && p('templateType,title') && e('ITTC,集成测试用例');      // 获取内置集成测试用例模板
r($upgrade->addBuiltInDocTemplateTest(8)) && p('templateType,title') && e('STTC,系统测试用例');      // 获取内置系统测试用例模板

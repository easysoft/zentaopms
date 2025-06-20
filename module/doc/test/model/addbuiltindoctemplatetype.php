#!/usr/bin/env php
<?php

/**

title=测试 docModel->addBuiltInDocTemplateType();
timeout=0
cid=1

- 获取计划分类
 - 属性root @2
 - 属性name @计划
 - 属性path @,1,
- 获取项目计划分类
 - 属性root @2
 - 属性name @项目计划
 - 属性path @,1,2,
- 获取需求计划分类
 - 属性root @2
 - 属性name @需求
 - 属性path @,3,
- 获取软件需求规格说明书分类
 - 属性root @2
 - 属性name @软件需求规格说明书
 - 属性path @,3,4,
- 获取设计分类
 - 属性root @2
 - 属性name @设计
 - 属性path @,5,
- 获取概要设计说明书分类
 - 属性root @2
 - 属性name @概要设计说明书
 - 属性path @,5,6,
- 获取详细设计说明书分类
 - 属性root @2
 - 属性name @详细设计说明书
 - 属性path @,5,7,
- 获取数据库设计文档分类
 - 属性root @2
 - 属性name @数据库设计文档
 - 属性path @,5,8,
- 获取接口设计文档分类
 - 属性root @2
 - 属性name @接口设计文档
 - 属性path @,5,9,
- 获取测试分类
 - 属性root @2
 - 属性name @测试
 - 属性path @,10,
- 获取集成测试用例分类
 - 属性root @2
 - 属性name @集成测试用例
 - 属性path @,10,11,
- 获取系统测试用例分类
 - 属性root @2
 - 属性name @系统测试用例
 - 属性path @,10,12,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('doclib')->gen(0);
zenData('module')->gen(0);
$docTester = new docTest();
r($docTester->addBuiltInDocTemplateTypeTest(1))  && p('root|name|path', '|') && e('2|计划|,1,');                 // 获取计划分类
r($docTester->addBuiltInDocTemplateTypeTest(2))  && p('root|name|path', '|') && e('2|项目计划|,1,2,');           // 获取项目计划分类
r($docTester->addBuiltInDocTemplateTypeTest(3))  && p('root|name|path', '|') && e('2|需求|,3,');                 // 获取需求计划分类
r($docTester->addBuiltInDocTemplateTypeTest(4))  && p('root|name|path', '|') && e('2|软件需求规格说明书|,3,4,'); // 获取软件需求规格说明书分类
r($docTester->addBuiltInDocTemplateTypeTest(5))  && p('root|name|path', '|') && e('2|设计|,5,');                 // 获取设计分类
r($docTester->addBuiltInDocTemplateTypeTest(6))  && p('root|name|path', '|') && e('2|概要设计说明书|,5,6,');     // 获取概要设计说明书分类
r($docTester->addBuiltInDocTemplateTypeTest(7))  && p('root|name|path', '|') && e('2|详细设计说明书|,5,7,');     // 获取详细设计说明书分类
r($docTester->addBuiltInDocTemplateTypeTest(8))  && p('root|name|path', '|') && e('2|数据库设计文档|,5,8,');     // 获取数据库设计文档分类
r($docTester->addBuiltInDocTemplateTypeTest(9))  && p('root|name|path', '|') && e('2|接口设计文档|,5,9,');       // 获取接口设计文档分类
r($docTester->addBuiltInDocTemplateTypeTest(10)) && p('root|name|path', '|') && e('2|测试|,10,');                // 获取测试分类
r($docTester->addBuiltInDocTemplateTypeTest(11)) && p('root|name|path', '|') && e('2|集成测试用例|,10,11,');     // 获取集成测试用例分类
r($docTester->addBuiltInDocTemplateTypeTest(12)) && p('root|name|path', '|') && e('2|系统测试用例|,10,12,');     // 获取系统测试用例分类

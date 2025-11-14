#!/usr/bin/env php
<?php

/**

title=测试 docModel->addBuiltInDocTemplateType();
timeout=0
cid=16039

- 检查计划分类 @1
- 检查项目计划分类 @1
- 检查需求计划分类 @1
- 检查软件需求规格说明书分类 @1
- 检查设计分类 @1
- 检查概要设计说明书分类 @1
- 检查详细设计说明书分类 @1
- 检查数据库设计文档分类 @1
- 检查接口设计文档分类 @1
- 检查测试分类 @1
- 检查集成测试用例分类 @1
- 检查系统测试用例分类 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('doclib')->gen(0);
zenData('module')->gen(0);
$docTester = new docTest();

$checkPlan   = array('root' => 2, 'name' => '计划',               'short' => 'plan',   'path' => ',1,');
$checkPP     = array('root' => 2, 'name' => '项目计划',           'short' => 'PP',     'path' => ',1,2,');
$checkStory  = array('root' => 2, 'name' => '需求',               'short' => 'story',  'path' => ',3,');
$checkSRS    = array('root' => 2, 'name' => '软件需求规格说明书', 'short' => 'SRS',    'path' => ',3,4,');
$checkDesign = array('root' => 2, 'name' => '设计',               'short' => 'design', 'path' => ',5,');
$checkHLDS   = array('root' => 2, 'name' => '概要设计说明书',     'short' => 'HLDS',   'path' => ',5,6,');
$checkDDS    = array('root' => 2, 'name' => '详细设计说明书',     'short' => 'DDS',    'path' => ',5,7,');
$checkDBDS   = array('root' => 2, 'name' => '数据库设计文档',     'short' => 'DBDS',   'path' => ',5,8,');
$checkADS    = array('root' => 2, 'name' => '接口设计文档',       'short' => 'ADS',    'path' => ',5,9,');
$checkTest   = array('root' => 2, 'name' => '测试',               'short' => 'test',   'path' => ',10,');
$checkITTC   = array('root' => 2, 'name' => '集成测试用例',       'short' => 'ITTC',   'path' => ',10,11,');
$checkSTTC   = array('root' => 2, 'name' => '系统测试用例',       'short' => 'STTC',   'path' => ',10,12,');

r($docTester->addBuiltInDocTemplateTypeTest(1, $checkPlan))    && p() && e('1'); // 检查计划分类
r($docTester->addBuiltInDocTemplateTypeTest(2, $checkPP))      && p() && e('1'); // 检查项目计划分类
r($docTester->addBuiltInDocTemplateTypeTest(3, $checkStory))   && p() && e('1'); // 检查需求计划分类
r($docTester->addBuiltInDocTemplateTypeTest(4, $checkSRS))     && p() && e('1'); // 检查软件需求规格说明书分类
r($docTester->addBuiltInDocTemplateTypeTest(5, $checkDesign))  && p() && e('1'); // 检查设计分类
r($docTester->addBuiltInDocTemplateTypeTest(6, $checkHLDS))    && p() && e('1'); // 检查概要设计说明书分类
r($docTester->addBuiltInDocTemplateTypeTest(7, $checkDDS))     && p() && e('1'); // 检查详细设计说明书分类
r($docTester->addBuiltInDocTemplateTypeTest(8, $checkDBDS))    && p() && e('1'); // 检查数据库设计文档分类
r($docTester->addBuiltInDocTemplateTypeTest(9, $checkADS))     && p() && e('1'); // 检查接口设计文档分类
r($docTester->addBuiltInDocTemplateTypeTest(10, $checkTest))   && p() && e('1'); // 检查测试分类
r($docTester->addBuiltInDocTemplateTypeTest(11, $checkITTC))   && p() && e('1'); // 检查集成测试用例分类
r($docTester->addBuiltInDocTemplateTypeTest(12, $checkSTTC))   && p() && e('1'); // 检查系统测试用例分类

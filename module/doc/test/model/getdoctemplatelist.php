#!/usr/bin/env php
<?php

/**

title=测试 docModel->getDocTemplateList();
timeout=0
cid=1

- 获取产品范围下的所有模板
 - 第0条的lib属性 @1
 - 第0条的title属性 @产品模板5
 - 第0条的templateType属性 @custom1
- 获取产品范围下的草稿模板
 - 第1条的lib属性 @1
 - 第1条的title属性 @产品模板4
 - 第1条的templateType属性 @custom1
- 获取产品范围下的已发布模板
 - 第0条的lib属性 @1
 - 第0条的title属性 @产品模板2
 - 第0条的templateType属性 @hardwareProductPlan
- 获取产品范围下的所有模板，按照title降序
 - 第0条的lib属性 @1
 - 第0条的title属性 @产品模板1
 - 第0条的templateType属性 @softwareProductPlan
- 获取项目范围下的所有模板
 - 第0条的lib属性 @2
 - 第0条的title属性 @项目模板10
 - 第0条的templateType属性 @custom2
- 获取项目范围下的草稿模板
 - 第1条的lib属性 @2
 - 第1条的title属性 @项目模板9
 - 第1条的templateType属性 @projectDetailedDesignSpecifica
- 获取项目范围下的已发布模板
 - 第0条的lib属性 @2
 - 第0条的title属性 @项目模板7
 - 第0条的templateType属性 @projectSoftwareRequirementsSpe
- 获取项目范围下的所有模板，按照title降序
 - 第0条的lib属性 @2
 - 第0条的title属性 @项目模板10
 - 第0条的templateType属性 @custom2
- 获取执行范围下的所有模板
 - 第0条的lib属性 @3
 - 第0条的title属性 @执行模板15
 - 第0条的templateType属性 @custom3
- 获取执行范围下的草稿模板
 - 第1条的lib属性 @3
 - 第1条的title属性 @执行模板14
 - 第1条的templateType属性 @custom3
- 获取执行范围下的已发布模板
 - 第0条的lib属性 @3
 - 第0条的title属性 @执行模板12
 - 第0条的templateType属性 @executionTestPlan
- 获取执行范围下的所有模板，按照title降序
 - 第0条的lib属性 @3
 - 第0条的title属性 @执行模板11
 - 第0条的templateType属性 @executionQualityAssurancePlan
- 获取个人范围下的所有模板
 - 第0条的lib属性 @4
 - 第0条的title属性 @个人模板20
 - 第0条的templateType属性 @custom4
- 获取个人范围下的草稿模板
 - 第1条的lib属性 @4
 - 第1条的title属性 @个人模板19
 - 第1条的templateType属性 @custom4
- 获取个人范围下的已发布模板
 - 第0条的lib属性 @4
 - 第0条的title属性 @个人模板17
 - 第0条的templateType属性 @personalWorkSummaryReport
- 获取个人范围下的所有模板，按照title降序
 - 第0条的lib属性 @4
 - 第0条的title属性 @个人模板16
 - 第0条的templateType属性 @personalWorkPlan

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('user')->gen(5);
su('admin');

$libs    = array(1, 2, 3, 4);
$types   = array('all', 'draft', 'released');
$orderBy = array('id_desc', 'title_asc');

$docTester = new docTest();
r($docTester->getDocTemplateListTest($libs[0], $types[0], $orderBy[0])) && p('0:lib,title,templateType') && e('1,产品模板5,custom1');                        // 获取产品范围下的所有模板
r($docTester->getDocTemplateListTest($libs[0], $types[1], $orderBy[0])) && p('1:lib,title,templateType') && e('1,产品模板4,custom1');                        // 获取产品范围下的草稿模板
r($docTester->getDocTemplateListTest($libs[0], $types[2], $orderBy[0])) && p('0:lib,title,templateType') && e('1,产品模板2,hardwareProductPlan');            // 获取产品范围下的已发布模板
r($docTester->getDocTemplateListTest($libs[0], $types[0], $orderBy[1])) && p('0:lib,title,templateType') && e('1,产品模板1,softwareProductPlan');            // 获取产品范围下的所有模板，按照title降序
r($docTester->getDocTemplateListTest($libs[1], $types[0], $orderBy[0])) && p('0:lib,title,templateType') && e('2,项目模板10,custom2');                       // 获取项目范围下的所有模板
r($docTester->getDocTemplateListTest($libs[1], $types[1], $orderBy[0])) && p('1:lib,title,templateType') && e('2,项目模板9,projectDetailedDesignSpecifica'); // 获取项目范围下的草稿模板
r($docTester->getDocTemplateListTest($libs[1], $types[2], $orderBy[0])) && p('0:lib,title,templateType') && e('2,项目模板7,projectSoftwareRequirementsSpe'); // 获取项目范围下的已发布模板
r($docTester->getDocTemplateListTest($libs[1], $types[0], $orderBy[1])) && p('0:lib,title,templateType') && e('2,项目模板10,custom2');                       // 获取项目范围下的所有模板，按照title降序
r($docTester->getDocTemplateListTest($libs[2], $types[0], $orderBy[0])) && p('0:lib,title,templateType') && e('3,执行模板15,custom3');                       // 获取执行范围下的所有模板
r($docTester->getDocTemplateListTest($libs[2], $types[1], $orderBy[0])) && p('1:lib,title,templateType') && e('3,执行模板14,custom3');                       // 获取执行范围下的草稿模板
r($docTester->getDocTemplateListTest($libs[2], $types[2], $orderBy[0])) && p('0:lib,title,templateType') && e('3,执行模板12,executionTestPlan');             // 获取执行范围下的已发布模板
r($docTester->getDocTemplateListTest($libs[2], $types[0], $orderBy[1])) && p('0:lib,title,templateType') && e('3,执行模板11,executionQualityAssurancePlan'); // 获取执行范围下的所有模板，按照title降序
r($docTester->getDocTemplateListTest($libs[3], $types[0], $orderBy[0])) && p('0:lib,title,templateType') && e('4,个人模板20,custom4');                       // 获取个人范围下的所有模板
r($docTester->getDocTemplateListTest($libs[3], $types[1], $orderBy[0])) && p('1:lib,title,templateType') && e('4,个人模板19,custom4');                       // 获取个人范围下的草稿模板
r($docTester->getDocTemplateListTest($libs[3], $types[2], $orderBy[0])) && p('0:lib,title,templateType') && e('4,个人模板17,personalWorkSummaryReport');     // 获取个人范围下的已发布模板
r($docTester->getDocTemplateListTest($libs[3], $types[0], $orderBy[1])) && p('0:lib,title,templateType') && e('4,个人模板16,personalWorkPlan');              // 获取个人范围下的所有模板，按照title降序

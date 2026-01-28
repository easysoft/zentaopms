#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::processRelated();
timeout=0
cid=18007

- 步骤1：处理所有类型关联属性afterCount @15
- 步骤2：仅项目和构建版本属性afterCount @2
- 步骤3：仅需求和Bug属性afterCount @3
- 步骤4：空值处理属性afterCount @0
- 步骤5：遗留Bug关联属性afterCount @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('release')->gen(0);
zenData('releaserelated')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$releaseTest = new releaseModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 测试步骤1：处理包含所有类型关联对象的发布
$releaseObj1 = new stdClass();
$releaseObj1->project  = ',1,2,';
$releaseObj1->build    = ',1,2,3,';
$releaseObj1->branch   = ',0,1,';
$releaseObj1->releases = ',2,3,';
$releaseObj1->stories  = ',1,2,3,';
$releaseObj1->bugs     = ',1,2,';
$releaseObj1->leftBugs = ',3,4,';
r($releaseTest->processRelatedTest(1, $releaseObj1)) && p('afterCount') && e('15'); // 步骤1：处理所有类型关联

// 测试步骤2：处理仅包含项目和构建版本关联的发布
$releaseObj2 = new stdClass();
$releaseObj2->project = ',1,';
$releaseObj2->build   = ',2,';
r($releaseTest->processRelatedTest(2, $releaseObj2)) && p('afterCount') && e('2'); // 步骤2：仅项目和构建版本

// 测试步骤3：处理仅包含需求和Bug关联的发布
$releaseObj3 = new stdClass();
$releaseObj3->stories = ',1,2,';
$releaseObj3->bugs    = ',1,';
r($releaseTest->processRelatedTest(3, $releaseObj3)) && p('afterCount') && e('3'); // 步骤3：仅需求和Bug

// 测试步骤4：处理空值和null值的发布对象
$releaseObj4 = new stdClass();
$releaseObj4->project  = '';
$releaseObj4->build    = '';
$releaseObj4->stories  = '';
r($releaseTest->processRelatedTest(4, $releaseObj4)) && p('afterCount') && e('0'); // 步骤4：空值处理

// 测试步骤5：处理包含遗留Bug关联的发布
$releaseObj5 = new stdClass();
$releaseObj5->leftBugs = ',1,2,3,';
r($releaseTest->processRelatedTest(5, $releaseObj5)) && p('afterCount') && e('3'); // 步骤5：遗留Bug关联
#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processCasesForBrowse();
timeout=0
cid=0

- 步骤1：空数组计数 @0
- 步骤2：包含场景的用例计数 @2
- 步骤3：不包含场景的用例计数 @1
- 步骤4：包含无效场景ID计数 @2
- 步骤5：验证ID转换第0条的id属性 @case_6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->id->range('1-10');
$table->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5,测试用例6,测试用例7,测试用例8,测试用例9,测试用例10');
$table->product->range('1,1,1,2,2,2,3,3,3,1');
$table->module->range('1001,1002,1003,1001,1002,1003,1001,1002,1003,1001');
$table->scene->range('1,2,3,0,0,1,2,3,0,0');
$table->status->range('wait,normal,blocked,investigate,done,wait,normal,blocked,investigate,done');
$table->type->range('feature,performance,config,install,feature,performance,config,install,feature,performance');
$table->pri->range('1,2,3,4,1,2,3,4,1,2');
$table->openedBy->range('admin,user1,user2,tester,admin,user1,user2,tester,admin,user1');
$table->gen(10);

$sceneTable = zenData('scene');
$sceneTable->id->range('1-5');
$sceneTable->title->range('场景1,场景2,场景3,场景4,场景5');
$sceneTable->product->range('1,1,2,2,3');
$sceneTable->module->range('1001,1002,1001,1002,1001');
$sceneTable->parent->range('0,1,0,3,0');
$sceneTable->grade->range('1,2,1,2,1');
$sceneTable->path->range(',1,,1,2,,3,,3,4,,5,');
$sceneTable->sort->range('1,2,3,4,5');
$sceneTable->openedBy->range('admin,user1,user2,admin,user1');
$sceneTable->deleted->range('0');
$sceneTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($testcaseTest->processCasesForBrowseTest(array()))) && p() && e('0'); // 步骤1：空数组计数
r(count($testcaseTest->processCasesForBrowseTest(array((object)array('id' => 1, 'title' => '&lt;测试用例1&gt;', 'scene' => 1))))) && p() && e('2'); // 步骤2：包含场景的用例计数
r(count($testcaseTest->processCasesForBrowseTest(array((object)array('id' => 4, 'title' => '测试用例4', 'scene' => 0))))) && p() && e('1'); // 步骤3：不包含场景的用例计数
r(count($testcaseTest->processCasesForBrowseTest(array((object)array('id' => 5, 'title' => '测试用例5', 'scene' => 999))))) && p() && e('2'); // 步骤4：包含无效场景ID计数
r($testcaseTest->processCasesForBrowseTest(array((object)array('id' => 6, 'title' => '测试用例6', 'scene' => 0)))) && p('0:id') && e('case_6'); // 步骤5：验证ID转换
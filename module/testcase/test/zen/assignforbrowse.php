#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForBrowse();
timeout=0
cid=0

- 步骤1:正常产品浏览(有项目)
 - 属性projectID @1
 - 属性browseType @all
 - 属性moduleID @0
- 步骤2:正常产品浏览(无项目)
 - 属性projectID @0
 - 属性browseType @all
 - 属性moduleID @0
- 步骤3:指定模块ID浏览
 - 属性moduleID @5
 - 属性hasModules @1
- 步骤4:分支产品浏览
 - 属性projectID @2
 - 属性browseType @all
- 步骤5:指定测试套件浏览
 - 属性suiteID @3
 - 属性caseType @feature
- 步骤6:按状态浏览
 - 属性browseType @wait
 - 属性param @0
- 步骤7:按需求浏览
 - 属性browseType @bystory
 - 属性param @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{2},branch{2},platform{1}');
$productTable->status->range('normal');
$productTable->createdBy->range('admin');
$productTable->createdDate->range('`2024-01-01 10:00:00`');
$productTable->deleted->range('0');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目A,项目B,项目C,项目D,项目E');
$projectTable->type->range('project');
$projectTable->model->range('scrum{2},waterfall{2},kanban{1}');
$projectTable->status->range('doing');
$projectTable->openedBy->range('admin');
$projectTable->openedDate->range('`2024-01-01 10:00:00`');
$projectTable->deleted->range('0');
$projectTable->gen(5);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1{5},2{3},3{2}');
$moduleTable->branch->range('0');
$moduleTable->name->range('模块A,模块B,模块C,模块D,模块E,模块F,模块G,模块H,模块I,模块J');
$moduleTable->type->range('case');
$moduleTable->parent->range('0');
$moduleTable->grade->range('1');
$moduleTable->deleted->range('0');
$moduleTable->gen(10);

$suiteTable = zenData('testsuite');
$suiteTable->id->range('1-5');
$suiteTable->name->range('测试套件A,测试套件B,测试套件C,测试套件D,测试套件E');
$suiteTable->type->range('public');
$suiteTable->product->range('1{3},2{2}');
$suiteTable->deleted->range('0');
$suiteTable->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$userTable->password->range('123456');
$userTable->role->range('admin,qa{5},dev{3},pm{1}');
$userTable->deleted->range('0');
$userTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignForBrowseTest(1, '0', 'all', 1, 0, 0, 0, 'feature')) && p('projectID,browseType,moduleID') && e('1,all,0'); // 步骤1:正常产品浏览(有项目)
r($testcaseTest->assignForBrowseTest(2, '0', 'all', 0, 0, 0, 0, 'feature')) && p('projectID,browseType,moduleID') && e('0,all,0'); // 步骤2:正常产品浏览(无项目)
r($testcaseTest->assignForBrowseTest(1, '0', 'all', 0, 0, 5, 0, 'feature')) && p('moduleID,hasModules') && e('5,1'); // 步骤3:指定模块ID浏览
r($testcaseTest->assignForBrowseTest(2, '1', 'all', 2, 0, 0, 0, 'feature')) && p('projectID,browseType') && e('2,all'); // 步骤4:分支产品浏览
r($testcaseTest->assignForBrowseTest(1, '0', 'all', 0, 0, 0, 3, 'feature')) && p('suiteID,caseType') && e('3,feature'); // 步骤5:指定测试套件浏览
r($testcaseTest->assignForBrowseTest(1, '0', 'wait', 0, 0, 0, 0, 'feature')) && p('browseType,param') && e('wait,0'); // 步骤6:按状态浏览
r($testcaseTest->assignForBrowseTest(1, '0', 'bystory', 0, 10, 0, 0, 'feature')) && p('browseType,param') && e('bystory,10'); // 步骤7:按需求浏览

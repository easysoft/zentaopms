#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignModuleTreeForBrowse();
timeout=0
cid=19075

- 步骤1：有产品ID和项目ID
 - 属性moduleTreeAssigned @1
 - 属性error @~~
- 步骤2：只有项目ID没有产品ID
 - 属性moduleTreeAssigned @1
 - 属性error @~~
- 步骤3：只有产品ID没有项目ID
 - 属性moduleTreeAssigned @1
 - 属性error @~~
- 步骤4：产品ID和项目ID都为0
 - 属性moduleTreeAssigned @1
 - 属性error @~~
- 步骤5：带有分支参数
 - 属性moduleTreeAssigned @1
 - 属性error @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$productTable->type->range('normal{8},branch{2}');
$productTable->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$projectTable->type->range('project{5},sprint{5}');
$projectTable->gen(10);

$moduleTable = zenData('module');
$moduleTable->id->range('1-20');
$moduleTable->root->range('1-10{2}');
$moduleTable->branch->range('0{15},1{5}');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10,模块11,模块12,模块13,模块14,模块15,模块16,模块17,模块18,模块19,模块20');
$moduleTable->parent->range('0{10},1-10{10}');
$moduleTable->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`,`,6,`,`,7,`,`,8,`,`,9,`,`,10,`,`,1,1,`,`,1,2,`,`,1,3,`,`,1,4,`,`,1,5,`,`,1,6,`,`,1,7,`,`,1,8,`,`,1,9,`,`,1,10,`');
$moduleTable->type->range('case{20}');
$moduleTable->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignModuleTreeForBrowseTest(1, '0', 1)) && p('moduleTreeAssigned,error') && e('1,~~'); // 步骤1：有产品ID和项目ID
r($testcaseTest->assignModuleTreeForBrowseTest(0, '0', 1)) && p('moduleTreeAssigned,error') && e('1,~~'); // 步骤2：只有项目ID没有产品ID
r($testcaseTest->assignModuleTreeForBrowseTest(1, '0', 0)) && p('moduleTreeAssigned,error') && e('1,~~'); // 步骤3：只有产品ID没有项目ID
r($testcaseTest->assignModuleTreeForBrowseTest(0, '0', 0)) && p('moduleTreeAssigned,error') && e('1,~~'); // 步骤4：产品ID和项目ID都为0
r($testcaseTest->assignModuleTreeForBrowseTest(2, '1', 2)) && p('moduleTreeAssigned,error') && e('1,~~'); // 步骤5：带有分支参数
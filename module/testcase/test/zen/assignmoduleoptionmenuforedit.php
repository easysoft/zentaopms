#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignModuleOptionMenuForEdit();
timeout=0
cid=0

- 步骤1：普通产品用例属性1001 @/1
- 步骤2：空模块处理 @/
- 步骤3：缺失模块处理属性9999 @~~
- 步骤4：空分支处理属性1002 @/2
- 步骤5：大ID模块处理属性1005 @/5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$moduleTable = zenData('module');
$moduleTable->id->range('1001-1010');
$moduleTable->root->range('1{5}, 2{5}');
$moduleTable->name->range('模块A,模块B,模块C,模块D,模块E,模块F,模块G,模块H,模块I,模块J');
$moduleTable->type->range('case');
$moduleTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：测试普通产品用例的模块选项菜单分配
$case1 = new stdclass();
$case1->product = 1;
$case1->module = 1001;
$case1->branch = '1';
$case1->lib = 0;
$case1->fromCaseID = 0;
r($testcaseTest->assignModuleOptionMenuForEditTest($case1)) && p('1001') && e('/1'); // 步骤1：普通产品用例

// 步骤2：测试空模块的边界情况处理
$case2 = new stdclass();
$case2->product = 1;
$case2->module = 0;
$case2->branch = '1';
$case2->lib = 0;
$case2->fromCaseID = 0;
r($testcaseTest->assignModuleOptionMenuForEditTest($case2)) && p('0') && e('/'); // 步骤2：空模块处理

// 步骤3：测试用例模块不在当前菜单中的处理
$case3 = new stdclass();
$case3->product = 1;
$case3->module = 9999;
$case3->branch = '1';
$case3->lib = 0;
$case3->fromCaseID = 0;
r($testcaseTest->assignModuleOptionMenuForEditTest($case3)) && p('9999') && e('~~'); // 步骤3：缺失模块处理

// 步骤4：测试空分支的用例模块选项菜单分配
$case4 = new stdclass();
$case4->product = 1;
$case4->module = 1002;
$case4->branch = '';
$case4->lib = 0;
$case4->fromCaseID = 0;
r($testcaseTest->assignModuleOptionMenuForEditTest($case4)) && p('1002') && e('/2'); // 步骤4：空分支处理

// 步骤5：测试大模块ID的边界情况
$case5 = new stdclass();
$case5->product = 2;
$case5->module = 1005;
$case5->branch = '0';
$case5->lib = 0;
$case5->fromCaseID = 0;
r($testcaseTest->assignModuleOptionMenuForEditTest($case5)) && p('1005') && e('/5'); // 步骤5：大ID模块处理
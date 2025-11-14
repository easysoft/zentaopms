#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setMenuForLibCaseEdit();
timeout=0
cid=19112

- 步骤1：项目模式设置菜单
 - 属性executed @1
 - 属性tabChecked @project
- 步骤2：用例库模式设置菜单
 - 属性executed @1
 - 属性tabChecked @caselib
- 步骤3：空libraries参数测试
 - 属性executed @1
 - 属性tabChecked @caselib
- 步骤4：执行模式设置菜单
 - 属性executed @1
 - 属性tabChecked @caselib
- 步骤5：其他模式设置菜单
 - 属性executed @1
 - 属性tabChecked @caselib

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（该方法不依赖数据库，不需要准备数据）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 准备测试数据
$case1 = new stdClass();
$case1->id = 1;
$case1->lib = 1;
$case1->title = '测试用例1';

$case2 = new stdClass();
$case2->id = 2;
$case2->lib = 2;
$case2->title = '测试用例2';

$libraries = array(
    1 => '用例库1',
    2 => '用例库2',
    3 => '用例库3'
);

$emptyLibraries = array();

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->setMenuForLibCaseEditTest($case1, $libraries, 'project')) && p('executed,tabChecked') && e('1,project'); // 步骤1：项目模式设置菜单
r($testcaseTest->setMenuForLibCaseEditTest($case1, $libraries, 'qa')) && p('executed,tabChecked') && e('1,caselib'); // 步骤2：用例库模式设置菜单
r($testcaseTest->setMenuForLibCaseEditTest($case2, $emptyLibraries, '')) && p('executed,tabChecked') && e('1,caselib'); // 步骤3：空libraries参数测试
r($testcaseTest->setMenuForLibCaseEditTest($case1, $libraries, 'execution')) && p('executed,tabChecked') && e('1,caselib'); // 步骤4：执行模式设置菜单
r($testcaseTest->setMenuForLibCaseEditTest($case2, $libraries, 'admin')) && p('executed,tabChecked') && e('1,caselib'); // 步骤5：其他模式设置菜单
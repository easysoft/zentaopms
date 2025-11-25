#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setMenuForLibCaseEdit();
timeout=0
cid=19111

- 步骤1：project标签菜单设置属性executed @1
- 步骤2：非project标签菜单设置属性executed @1
- 步骤3：简单用例对象属性executed @1
- 步骤4：空库列表属性executed @1
- 步骤5：无tab参数属性executed @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-10');
$case->lib->range('1-3');
$case->title->range('测试用例{1-10}');
$case->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 构建测试数据
$testCase = new stdClass();
$testCase->lib = 1;
$testCase->project = 1;

$libraries = array(1 => '测试库1', 2 => '测试库2');

r($testcaseTest->setMenuForLibCaseEditTest($testCase, $libraries, 'project')) && p('executed') && e('1'); // 步骤1：project标签菜单设置

// 构建有lib属性的空用例对象
$emptyCase = new stdClass();
$emptyCase->lib = 1;

r($testcaseTest->setMenuForLibCaseEditTest($testCase, $libraries, 'qa')) && p('executed') && e('1'); // 步骤2：非project标签菜单设置
r($testcaseTest->setMenuForLibCaseEditTest($emptyCase, $libraries, 'project')) && p('executed') && e('1'); // 步骤3：简单用例对象
r($testcaseTest->setMenuForLibCaseEditTest($testCase, array(), 'project')) && p('executed') && e('1'); // 步骤4：空库列表
r($testcaseTest->setMenuForLibCaseEditTest($testCase, $libraries, '')) && p('executed') && e('1'); // 步骤5：无tab参数
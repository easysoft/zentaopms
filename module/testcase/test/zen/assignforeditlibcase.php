#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForEditLibCase();
timeout=0
cid=19069

- 步骤1：正常情况属性executed @1
- 步骤2：检查isLibCase属性isLibCase @1
- 步骤3：检查libID属性libID @1
- 步骤4：第二个库属性executed @1
- 步骤5：第三个库属性executed @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseZenTest = new testcaseZenTest();

// 准备测试数据
$validCase = new stdClass();
$validCase->id = 1;
$validCase->title = '测试用例标题';
$validCase->lib = 1;

$validLibraries = array(
    1 => '用例库1',
    2 => '用例库2',
    3 => '用例库3'
);

$secondCase = new stdClass();
$secondCase->id = 2;
$secondCase->title = '第二个测试用例';
$secondCase->lib = 2;

$thirdCase = new stdClass();
$thirdCase->id = 3;
$thirdCase->title = '第三个测试用例';
$thirdCase->lib = 3;

$emptyTitleCase = new stdClass();
$emptyTitleCase->id = 4;
$emptyTitleCase->title = '';
$emptyTitleCase->lib = 1;

$emptyLibraries = array();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseZenTest->assignForEditLibCaseTest($validCase, $validLibraries)) && p('executed') && e('1'); // 步骤1：正常情况
r($testcaseZenTest->assignForEditLibCaseTest($validCase, $validLibraries)) && p('isLibCase') && e('1'); // 步骤2：检查isLibCase
r($testcaseZenTest->assignForEditLibCaseTest($validCase, $validLibraries)) && p('libID') && e('1'); // 步骤3：检查libID
r($testcaseZenTest->assignForEditLibCaseTest($secondCase, $validLibraries)) && p('executed') && e('1'); // 步骤4：第二个库
r($testcaseZenTest->assignForEditLibCaseTest($thirdCase, $validLibraries)) && p('executed') && e('1'); // 步骤5：第三个库
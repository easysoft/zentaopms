#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignShowImportVars();
timeout=0
cid=0

- 执行testcaseTest模块的assignShowImportVarsTest方法，参数是1, '0', $caseData1, 10, 1, 100 属性allCount @3
- 执行testcaseTest模块的assignShowImportVarsTest方法，参数是1, '0', array 属性error @noData
- 执行testcaseTest模块的assignShowImportVarsTest方法，参数是1, '0', $largeCaseData, 50, 1, 10 属性allCount @150
- 执行testcaseTest模块的assignShowImportVarsTest方法，参数是1, '0', $largeCaseData, 50, 1, 0 属性showMaxImportPage @1
- 执行testcaseTest模块的assignShowImportVarsTest方法，参数是1, '0', $largeCaseData, 50, 2, 10 属性allPager @15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('product')->gen('5');
zenData('branch')->gen('5');
zenData('module')->gen('10');
zenData('story')->gen('15');
zenData('user')->gen('1');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常情况 - 提供有效数据
$caseData1 = array(
    array('title' => '测试用例1', 'module' => 1),
    array('title' => '测试用例2', 'module' => 2),
    array('title' => '测试用例3', 'module' => 3)
);
r($testcaseTest->assignShowImportVarsTest(1, '0', $caseData1, 10, 1, 100)) && p('allCount') && e('3');

// 测试步骤2：空用例数据 - 期望错误处理
r($testcaseTest->assignShowImportVarsTest(1, '0', array(), 0, 1, 100)) && p('error') && e('noData');

// 测试步骤3：大量用例数据 - 超过最大导入数量(创建150个用例，超过系统限制100)
$largeCaseData = array();
for($i = 1; $i <= 150; $i++) {
    $largeCaseData[] = array('title' => "测试用例{$i}", 'module' => ($i % 5) + 1);
}
r($testcaseTest->assignShowImportVarsTest(1, '0', $largeCaseData, 50, 1, 10)) && p('allCount') && e('150');

// 测试步骤4：边界值测试 - maxImport=0，应该显示导入限制页面
r($testcaseTest->assignShowImportVarsTest(1, '0', $largeCaseData, 50, 1, 0)) && p('showMaxImportPage') && e('1');

// 测试步骤5：分页功能测试 - 测试第二页
r($testcaseTest->assignShowImportVarsTest(1, '0', $largeCaseData, 50, 2, 10)) && p('allPager') && e('15');
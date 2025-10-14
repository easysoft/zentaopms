#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildBugDataForExport();
timeout=0
cid=0

- 步骤1：正常Bug类型导出 @*<h3>解决Bug</h3><table>*Bug测试标题1*
- 步骤2：遗留Bug类型导出 @*<h3>遗留Bug</h3><table>*Bug测试标题6*
- 步骤3：无Bug数据的导出 @<h3>解决Bug</h3>
- 步骤4：空Bug ID列表的导出 @<h3>遗留Bug</h3>
- 步骤5：无效Bug类型导出 @*<h3>解决Bug</h3><table>*Bug测试标题1*

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 2. 创建模拟数据，避免数据库依赖
// 创建测试实例（变量名与模块名一致）
$releaseTest = new releaseTest();

// 创建模拟的release对象
$release1 = new stdclass();
$release1->id = 1;
$release1->bugs = '1,2,3';
$release1->leftBugs = '6,7';

$release2 = new stdclass();
$release2->id = 2;
$release2->bugs = '4,5';
$release2->leftBugs = '';

$release3 = new stdclass();
$release3->id = 3;
$release3->bugs = '';
$release3->leftBugs = '8,9';

$release4 = new stdclass();
$release4->id = 4;
$release4->bugs = '';
$release4->leftBugs = '';

// 3. 用户登录（选择合适角色）
su('admin');

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($releaseTest->buildBugDataForExportTest($release1, 'bug')) && p() && e('*<h3>解决Bug</h3><table>*Bug测试标题1*'); // 步骤1：正常Bug类型导出
r($releaseTest->buildBugDataForExportTest($release1, 'leftbug')) && p() && e('*<h3>遗留Bug</h3><table>*Bug测试标题6*'); // 步骤2：遗留Bug类型导出
r($releaseTest->buildBugDataForExportTest($release3, 'bug')) && p() && e('<h3>解决Bug</h3>'); // 步骤3：无Bug数据的导出
r($releaseTest->buildBugDataForExportTest($release4, 'leftbug')) && p() && e('<h3>遗留Bug</h3>'); // 步骤4：空Bug ID列表的导出
r($releaseTest->buildBugDataForExportTest($release1, 'invalid')) && p() && e('*<h3>解决Bug</h3><table>*Bug测试标题1*'); // 步骤5：无效Bug类型导出
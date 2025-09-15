#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewCaselib();
timeout=0
cid=0

- 步骤1：setting视图下customSearch条件预览用例库测试用例 @2
- 步骤2：setting视图下预定义条件预览用例库测试用例 @3
- 步骤3：list视图下根据ID列表预览测试用例 @3
- 步骤4：测试空ID列表的情况 @0
- 步骤5：测试无效用例库ID的情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testsuiteTable = zenData('testsuite');
$testsuiteTable->id->range('1-3');
$testsuiteTable->name->range('功能测试用例库,性能测试用例库,API测试用例库');
$testsuiteTable->type->range('library{3}');
$testsuiteTable->gen(3);

$caseTable = zenData('case');
$caseTable->id->range('1-10');
$caseTable->lib->range('1{5},2{3},999{2}');
$caseTable->product->range('1{8},2{2}');
$caseTable->title->range('测试用例1{2},测试用例2{3},性能测试用例{2},API测试用例{2},界面测试用例{1}');
$caseTable->status->range('normal{7},blocked{1},investigate{1},draft{1}');
$caseTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->previewCaselibTest('setting', array('action' => 'preview', 'caselib' => 1, 'condition' => 'customSearch', 'field' => array('title'), 'operator' => array('include'), 'value' => array('测试用例'), 'andor' => array('and')), '')) && p() && e('2'); // 步骤1：setting视图下customSearch条件预览用例库测试用例
r($docTest->previewCaselibTest('setting', array('action' => 'preview', 'caselib' => 1, 'condition' => 'all'), '')) && p() && e('3'); // 步骤2：setting视图下预定义条件预览用例库测试用例
r($docTest->previewCaselibTest('list', array(), '1,2,3')) && p() && e('3'); // 步骤3：list视图下根据ID列表预览测试用例
r($docTest->previewCaselibTest('list', array(), '')) && p() && e('0'); // 步骤4：测试空ID列表的情况
r($docTest->previewCaselibTest('setting', array('action' => 'preview', 'caselib' => 999, 'condition' => 'all'), '')) && p() && e('0'); // 步骤5：测试无效用例库ID的情况
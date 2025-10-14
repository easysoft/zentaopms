#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductCase();
timeout=0
cid=0

- 步骤1：预览设置页面自定义搜索 @2
- 步骤2：预览设置页面条件搜索 @3
- 步骤3：有效ID列表 @3
- 步骤4：空参数情况 @0
- 步骤5：无效视图类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('case');
$table->id->range('1-100');
$table->product->range('1-5');
$table->title->range('登录功能测试用例,用户注册测试用例,产品搜索测试用例');
$table->status->range('normal{60},blocked{40}');
$table->type->range('feature{80},performance{20}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->previewProductCaseTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch'), '')) && p() && e('2'); // 步骤1：预览设置页面自定义搜索
r($docTest->previewProductCaseTest('setting', array('action' => 'preview', 'product' => 2, 'condition' => 'all'), '')) && p() && e('3'); // 步骤2：预览设置页面条件搜索
r($docTest->previewProductCaseTest('list', array(), '1,2,3')) && p() && e('3'); // 步骤3：有效ID列表
r($docTest->previewProductCaseTest('setting', array(), '')) && p() && e('0'); // 步骤4：空参数情况
r($docTest->previewProductCaseTest('invalid', array(), '')) && p() && e('0'); // 步骤5：无效视图类型
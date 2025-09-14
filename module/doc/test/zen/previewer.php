#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewER();
timeout=0
cid=0

- 步骤1：正常预览设置模式 @epic
- 步骤2：列表模式ID列表预览 @3
- 步骤3：自定义搜索预览 @2
- 步骤4：空参数预览 @0
- 步骤5：无效视图类型预览 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->title->range('业务需求{1-10}');
$table->product->range('1-3');
$table->type->range('epic');
$table->status->range('active,draft,closed');
$table->pri->range('1-4');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('产品A,产品B,产品C');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result1 = $docTest->previewERTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'all'));
r($result1['data'][0]->type) && p() && e('epic'); // 步骤1：正常预览设置模式

$result2 = $docTest->previewERTest('list', array(), '1,2,3');
r(count($result2['data'])) && p() && e('3'); // 步骤2：列表模式ID列表预览

$result3 = $docTest->previewERTest('setting', array('action' => 'preview', 'product' => 2, 'condition' => 'customSearch', 'field' => array('title')));
r($result3['data'][0]->product) && p() && e('2'); // 步骤3：自定义搜索预览

$result4 = $docTest->previewERTest('setting', array());
r(count($result4['data'])) && p() && e('0'); // 步骤4：空参数预览

$result5 = $docTest->previewERTest('invalid', array(), '');
r(count($result5['data'])) && p() && e('0'); // 步骤5：无效视图类型预览
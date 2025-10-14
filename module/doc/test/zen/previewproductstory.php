#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductStory();
timeout=0
cid=0

- 步骤1：preview模式有效产品ID @2
- 步骤2：preview模式无效产品ID @0
- 步骤3：preview模式自定义搜索 @1
- 步骤4：list模式有效ID列表 @3
- 步骤5：list模式空ID列表 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1');
$table->title->range('产品需求1,产品需求2,产品需求3,产品需求4,产品需求5');
$table->status->range('active');
$table->type->range('story');
$table->stage->range('planned,developing,testing');
$table->pri->range('1-4');
$table->estimate->range('3-8');
$table->assignedTo->range('admin,user1,user2');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->status->range('normal');
$productTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $docTest->previewProductStoryTest('setting', array('action' => 'preview', 'product' => 1), '');
r(count($result1['data'])) && p() && e('2'); // 步骤1：preview模式有效产品ID

$result2 = $docTest->previewProductStoryTest('setting', array('action' => 'preview', 'product' => 0), '');
r(count($result2['data'])) && p() && e('0'); // 步骤2：preview模式无效产品ID

$result3 = $docTest->previewProductStoryTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('title'), 'operator' => array('include'), 'value' => array('搜索')), '');
r(count($result3['data'])) && p() && e('1'); // 步骤3：preview模式自定义搜索

$result4 = $docTest->previewProductStoryTest('list', array(), '1,2,3');
r(count($result4['data'])) && p() && e('3'); // 步骤4：list模式有效ID列表

$result5 = $docTest->previewProductStoryTest('list', array(), '');
r(count($result5['data'])) && p() && e('0'); // 步骤5：list模式空ID列表
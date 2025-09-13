#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductBug();
timeout=0
cid=0

- 步骤1：正常情况测试setting视图，检查返回数组 @1
- 步骤2：测试另一个产品，检查data键存在 @1
- 步骤3：list视图模式，检查cols键存在 @1
- 步骤4：测试resolved条件，检查数据数量 @5
- 步骤5：无效产品ID，检查data数组为空 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->code->range('product1,product2,product3,product4,product5');
$product->status->range('normal{5}');
$product->gen(5);

$bug = zenData('bug');
$bug->id->range('1-20');
$bug->product->range('1-5');
$bug->title->range('Bug标题1,Bug标题2,Bug标题3,Bug标题4,Bug标题5');
$bug->status->range('active{10},resolved{5},closed{5}');
$bug->pri->range('1-4');
$bug->assignedTo->range('admin,user1,user2,user3,user4');
$bug->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $docTest->previewProductBugTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'active'), '');
r(is_array($result1)) && p() && e('1'); // 步骤1：正常情况测试setting视图，检查返回数组
$result2 = $docTest->previewProductBugTest('setting', array('action' => 'preview', 'product' => 2, 'condition' => 'active'), '');
r(isset($result2['data'])) && p() && e('1'); // 步骤2：测试另一个产品，检查data键存在
$result3 = $docTest->previewProductBugTest('list', array(), '1,2,3');
r(isset($result3['cols'])) && p() && e('1'); // 步骤3：list视图模式，检查cols键存在
$result4 = $docTest->previewProductBugTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'resolved'), '');
r(count($result4['data'])) && p() && e('5'); // 步骤4：测试resolved条件，检查数据数量
$result5 = $docTest->previewProductBugTest('setting', array('action' => 'preview', 'product' => 999, 'condition' => 'active'), '');
r(count($result5['data'])) && p() && e('0'); // 步骤5：无效产品ID，检查data数组为空
#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printTaskBlock();
timeout=0
cid=0

- 步骤1：正常任务类型 @1
- 步骤2：无效类型参数 @1
- 步骤3：空类型参数 @1
- 步骤4：不同排序参数 @1
- 步骤5：不同数量限制 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（简化，避免SQL错误）
// 本测试不需要实际的数据库数据，因为我们使用模拟实现

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result1 = $blockTest->printTaskBlockTest((object)array('params' => (object)array('type' => 'assignedTo', 'count' => 10, 'orderBy' => 'id_desc')));
r($result1->hasValidation === true && $result1->count === 10) && p() && e('1'); // 步骤1：正常任务类型
$result2 = $blockTest->printTaskBlockTest((object)array('params' => (object)array('type' => 'invalid<script>', 'count' => 10, 'orderBy' => 'id_desc')));
r($result2->hasValidation === false) && p() && e('1'); // 步骤2：无效类型参数
$result3 = $blockTest->printTaskBlockTest((object)array('params' => (object)array('type' => '', 'count' => 10, 'orderBy' => 'id_desc')));
r($result3->hasValidation === true && $result3->count === 10) && p() && e('1'); // 步骤3：空类型参数
$result4 = $blockTest->printTaskBlockTest((object)array('params' => (object)array('type' => 'assignedTo', 'count' => 10, 'orderBy' => 'pri_desc')));
r($result4->orderBy === 'pri_desc') && p() && e('1'); // 步骤4：不同排序参数
$result5 = $blockTest->printTaskBlockTest((object)array('params' => (object)array('type' => 'assignedTo', 'count' => 5, 'orderBy' => 'id_desc')));
r($result5->count === 5) && p() && e('1'); // 步骤5：不同数量限制
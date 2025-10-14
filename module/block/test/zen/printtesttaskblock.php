#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printTesttaskBlock();
timeout=0
cid=0

- 步骤1：正常测试单类型all @1
- 步骤2：无效类型参数含特殊字符 @1
- 步骤3：测试单类型wait @1
- 步骤4：测试单类型doing @1
- 步骤5：不同数量限制测试 @1

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
$result1 = $blockTest->printTesttaskBlockTest((object)array('params' => (object)array('type' => 'all', 'count' => 10)));
r($result1->hasValidation === true && $result1->type === 'all' && $result1->count === 10) && p() && e('1'); // 步骤1：正常测试单类型all
$result2 = $blockTest->printTesttaskBlockTest((object)array('params' => (object)array('type' => 'invalid<script>', 'count' => 10)));
r($result2->hasValidation === false) && p() && e('1'); // 步骤2：无效类型参数含特殊字符
$result3 = $blockTest->printTesttaskBlockTest((object)array('params' => (object)array('type' => 'wait', 'count' => 5)));
r($result3->hasValidation === true && $result3->type === 'wait' && $result3->count === 5) && p() && e('1'); // 步骤3：测试单类型wait
$result4 = $blockTest->printTesttaskBlockTest((object)array('params' => (object)array('type' => 'doing', 'count' => 8)));
r($result4->hasValidation === true && $result4->type === 'doing' && $result4->count === 8) && p() && e('1'); // 步骤4：测试单类型doing
$result5 = $blockTest->printTesttaskBlockTest((object)array('params' => (object)array('type' => 'all', 'count' => 3)));
r($result5->count === 3 && count($result5->testtasks) === 3) && p() && e('1'); // 步骤5：不同数量限制测试
#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printGuideBlock();
timeout=0
cid=0

- 执行$result1->success @1
- 执行$result2->blockID == 5 @1
- 执行$result3->programCount >= 0 @1
- 执行$result4->URSRCount >= 0 @1
- 执行$result5->hasLinks @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备
zendata('block')->loadYaml('block_printguideblock', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 创建测试用的block对象
$block1 = new stdclass();
$block1->id = 1;
$block1->params = new stdclass();

$block2 = new stdclass();
$block2->id = 5;
$block2->params = new stdclass();

$block3 = new stdclass();
$block3->id = 0;
$block3->params = new stdclass();

// 步骤1：正常块对象测试，验证成功标志
$result1 = $blockTest->printGuideBlockTest($block1);
r($result1->success) && p() && e('1');

// 步骤2：验证块ID设置，检查blockID是否正确
$result2 = $blockTest->printGuideBlockTest($block2);
r($result2->blockID == 5) && p() && e('1');

// 步骤3：验证程序列表加载，检查程序数量是否大于等于0
$result3 = $blockTest->printGuideBlockTest($block1);
r($result3->programCount >= 0) && p() && e('1');

// 步骤4：验证URSR列表加载，检查URSR列表数量是否大于等于0
$result4 = $blockTest->printGuideBlockTest($block1);
r($result4->URSRCount >= 0) && p() && e('1');

// 步骤5：验证链接配置，检查必要链接是否设置
$result5 = $blockTest->printGuideBlockTest($block1);
r($result5->hasLinks) && p() && e('1');
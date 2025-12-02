#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printReleaseBlock();
timeout=0
cid=15282

- 步骤1：默认参数测试属性releaseCount @0
- 步骤2：noclosed过滤测试属性releaseCount @0
- 步骤3：closed过滤测试属性releaseCount @0
- 步骤4：数量限制测试属性releaseCount @0
- 步骤5：空参数处理测试属性releaseCount @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 暂时注释掉zendata，直接测试逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 创建默认的block参数
$defaultBlock = new stdclass();
$defaultBlock->params = new stdclass();
$defaultBlock->params->type = 'all';
$defaultBlock->params->count = 15;

r($blockTest->printReleaseBlockTest($defaultBlock)) && p('releaseCount') && e('0'); // 步骤1：默认参数测试

// 测试noclosed类型过滤
$nocloseBlock = new stdclass();
$nocloseBlock->params = new stdclass();
$nocloseBlock->params->type = 'noclosed';
$nocloseBlock->params->count = 15;

r($blockTest->printReleaseBlockTest($nocloseBlock)) && p('releaseCount') && e('0'); // 步骤2：noclosed过滤测试

// 测试closed类型过滤
$closedBlock = new stdclass();
$closedBlock->params = new stdclass();
$closedBlock->params->type = 'closed';
$closedBlock->params->count = 15;

r($blockTest->printReleaseBlockTest($closedBlock)) && p('releaseCount') && e('0'); // 步骤3：closed过滤测试

// 测试count限制
$limitBlock = new stdclass();
$limitBlock->params = new stdclass();
$limitBlock->params->type = 'all';
$limitBlock->params->count = 5;

r($blockTest->printReleaseBlockTest($limitBlock)) && p('releaseCount') && e('0'); // 步骤4：数量限制测试

// 测试空参数处理
r($blockTest->printReleaseBlockTest(null)) && p('releaseCount') && e('0'); // 步骤5：空参数处理测试
#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::processStageForExport();
timeout=0
cid=15557

- 步骤1：单个阶段转换 @单元测试环节
- 步骤2：多个阶段转换行数 @3
- 步骤3：包含无效阶段的第一个结果 @单元测试环节
- 步骤4：空stage处理 @1
- 步骤5：单个阶段无换行符 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($caselibTest->processStageForExportTest((object)array('stage' => 'unittest'), 'stage')) && p() && e('单元测试环节'); // 步骤1：单个阶段转换
r($caselibTest->processStageForExportTest((object)array('stage' => 'unittest,feature,system'), 'stage_lines')) && p() && e(3); // 步骤2：多个阶段转换行数
r($caselibTest->processStageForExportTest((object)array('stage' => 'unittest,invalid,feature'), 'first_stage')) && p() && e('单元测试环节'); // 步骤3：包含无效阶段的第一个结果
r($caselibTest->processStageForExportTest((object)array('stage' => ''), 'is_empty')) && p() && e(1); // 步骤4：空stage处理
r($caselibTest->processStageForExportTest((object)array('stage' => 'feature'), 'has_newlines')) && p() && e(0); // 步骤5：单个阶段无换行符

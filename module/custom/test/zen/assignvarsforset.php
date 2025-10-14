#!/usr/bin/env php
<?php

/**

title=测试 customZen::assignVarsForSet();
timeout=0
cid=0

- 步骤1：正常story模块priList字段 @executed
- 步骤2：project模块unitList字段特殊处理 @executed_unitList
- 步骤3：story模块review字段特殊处理 @executed_review
- 步骤4：bug模块longlife字段特殊处理 @executed_longlife
- 步骤5：其他模块字段的一般情况 @executed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('lang');
$table->lang->range('zh-cn, en, all');
$table->module->range('story, project, bug');
$table->section->range('priList, unitList, longlife');
$table->key->range('test1, test2, test3');
$table->value->range('高, 中, 低');
$table->vision->range('rnd');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$customTest = new customTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($customTest->assignVarsForSetTest('story', 'priList', 'zh-cn', 'zh-cn')) && p() && e('executed'); // 步骤1：正常story模块priList字段
r($customTest->assignVarsForSetTest('project', 'unitList', 'zh-cn', 'zh-cn')) && p() && e('executed_unitList'); // 步骤2：project模块unitList字段特殊处理
r($customTest->assignVarsForSetTest('story', 'review', 'zh-cn', 'zh-cn')) && p() && e('executed_review'); // 步骤3：story模块review字段特殊处理
r($customTest->assignVarsForSetTest('bug', 'longlife', 'zh-cn', 'zh-cn')) && p() && e('executed_longlife'); // 步骤4：bug模块longlife字段特殊处理
r($customTest->assignVarsForSetTest('task', 'typeList', 'all', 'zh-cn')) && p() && e('executed'); // 步骤5：其他模块字段的一般情况
#!/usr/bin/env php
<?php

/**

title=测试 customZen::setFieldListForSet();
timeout=0
cid=0

- 步骤1：正常情况story+priList @1
- 步骤2：project+unitList组合 @1
- 步骤3：story+review组合 @1
- 步骤4：bug+longlife组合 @1
- 步骤5：task+priList其他情况 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('lang');
$table->id->range('1-8');
$table->lang->range('zh-cn{4}, en{4}');
$table->module->range('story,story,project,bug,story,story,project,task');
$table->section->range('priList,review,unitList,longlife,priList,review,unitList,priList');
$table->key->range('1,needReview,CNY,day,2,forceReview,USD,3');
$table->value->range('高,1,人民币,天,中,admin,美元,低');
$table->system->range('1');
$table->vision->range('rnd');
$table->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$customTest = new customTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($customTest->setFieldListForSetTest('story', 'priList')) && p() && e('1'); // 步骤1：正常情况story+priList
r($customTest->setFieldListForSetTest('project', 'unitList')) && p() && e('1'); // 步骤2：project+unitList组合
r($customTest->setFieldListForSetTest('story', 'review')) && p() && e('1'); // 步骤3：story+review组合
r($customTest->setFieldListForSetTest('bug', 'longlife')) && p() && e('1'); // 步骤4：bug+longlife组合
r($customTest->setFieldListForSetTest('task', 'priList')) && p() && e('1'); // 步骤5：task+priList其他情况
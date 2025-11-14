#!/usr/bin/env php
<?php

/**

title=测试 actionZen::getConfirmNoMessage();
timeout=0
cid=14970

- 步骤1：名称和代码都重复 @系统内已有同名、同代号的产品，恢复后名称为"product_1"、代号为"PD0001_1"。
- 步骤2：仅名称重复 @系统内已有同名的研发需求，恢复后名称为"story_1"。
- 步骤3：仅代码重复 @系统内已有同名、同代号的任务，恢复后名称为"T0001_1"、代号为"T0001_1"。
- 步骤4：名称和代码都不重复 @0
- 步骤5：代码为空的情况下仅名称重复 @系统内已有同名的Bug，恢复后名称为"bug_1"。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$actionTest = new actionZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($actionTest->getConfirmNoMessageTest('product', 'product_1', 'PD0001_1', 'product', 'PD0001', 'both')) && p() && e('系统内已有同名、同代号的产品，恢复后名称为"product_1"、代号为"PD0001_1"。'); // 步骤1：名称和代码都重复
r($actionTest->getConfirmNoMessageTest('story', 'story_1', '', 'story', '', 'name')) && p() && e('系统内已有同名的研发需求，恢复后名称为"story_1"。'); // 步骤2：仅名称重复
r($actionTest->getConfirmNoMessageTest('', 'T0001_1', 'T0001_1', '', 'T0001', 'code')) && p() && e('系统内已有同名、同代号的任务，恢复后名称为"T0001_1"、代号为"T0001_1"。'); // 步骤3：仅代码重复
r($actionTest->getConfirmNoMessageTest('project1', 'project1_1', 'P001_1', 'project2', 'P002', 'none')) && p() && e('0'); // 步骤4：名称和代码都不重复
r($actionTest->getConfirmNoMessageTest('bug', 'bug_1', '', 'bug', '', 'name_only')) && p() && e('系统内已有同名的Bug，恢复后名称为"bug_1"。'); // 步骤5：代码为空的情况下仅名称重复
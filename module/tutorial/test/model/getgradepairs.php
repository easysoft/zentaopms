#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getGradePairs();
timeout=0
cid=19438

- 步骤1：测试story类型属性1 @SR
- 步骤2：测试requirement类型属性1 @UR
- 步骤3：测试epic类型属性1 @BR
- 步骤4：测试空字符串类型 @0
- 步骤5：测试无效类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('storygrade');
$table->type->range('story,requirement,epic');
$table->grade->range(1);
$table->name->range('SR,UR,BR');
$table->status->range('enable');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getGradePairsTest('story')) && p('1') && e('SR'); // 步骤1：测试story类型
r($tutorialTest->getGradePairsTest('requirement')) && p('1') && e('UR'); // 步骤2：测试requirement类型
r($tutorialTest->getGradePairsTest('epic')) && p('1') && e('BR'); // 步骤3：测试epic类型
r($tutorialTest->getGradePairsTest('')) && p() && e('0'); // 步骤4：测试空字符串类型
r($tutorialTest->getGradePairsTest('invalid')) && p() && e('0'); // 步骤5：测试无效类型
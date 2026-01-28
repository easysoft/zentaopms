#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getRepoPairs();
timeout=0
cid=19462

- 步骤1：验证返回数组包含预期的键值对属性1 @[git] Test repo
- 步骤2：验证返回数组长度为1 @1
- 步骤3：验证返回数组的第一个键是1 @1
- 步骤4：验证返回数组是否为数组类型 @1
- 步骤5：验证返回数组包含git关键词 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getRepoPairsTest()) && p('1') && e('[git] Test repo'); // 步骤1：验证返回数组包含预期的键值对
r(count($tutorialTest->getRepoPairsTest())) && p() && e('1'); // 步骤2：验证返回数组长度为1
r(array_keys($tutorialTest->getRepoPairsTest())) && p('0') && e('1'); // 步骤3：验证返回数组的第一个键是1
r(is_array($tutorialTest->getRepoPairsTest())) && p() && e('1'); // 步骤4：验证返回数组是否为数组类型
r(strpos($tutorialTest->getRepoPairsTest()[1], 'git') !== false) && p() && e('1'); // 步骤5：验证返回数组包含git关键词
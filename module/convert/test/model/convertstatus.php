#!/usr/bin/env php
<?php

/**

title=测试 convertModel::convertStatus();
timeout=0
cid=15767

- 步骤1：测试关系映射中存在的状态转换 @active
- 步骤2：测试关系映射中不存在的状态转换(testcase类型) @normal
- 步骤3：测试关系映射中不存在的状态转换(feedback类型) @normal
- 步骤4：测试关系映射中不存在的状态转换(task类型) @wait
- 步骤5：测试关系映射中不存在的状态转换(bug类型) @active

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->convertStatusTest('story', 'open', '1', array('zentaoStatus1' => array('open' => 'active')))) && p() && e('active'); // 步骤1：测试关系映射中存在的状态转换
r($convertTest->convertStatusTest('testcase', 'unknown', '2', array())) && p() && e('normal'); // 步骤2：测试关系映射中不存在的状态转换(testcase类型)
r($convertTest->convertStatusTest('feedback', 'unknown', '3', array())) && p() && e('normal'); // 步骤3：测试关系映射中不存在的状态转换(feedback类型)
r($convertTest->convertStatusTest('task', 'unknown', '4', array())) && p() && e('wait'); // 步骤4：测试关系映射中不存在的状态转换(task类型)
r($convertTest->convertStatusTest('bug', 'unknown', '5', array())) && p() && e('active'); // 步骤5：测试关系映射中不存在的状态转换(bug类型)
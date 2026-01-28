#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBugs();
timeout=0
cid=19406

- 步骤1：获取Bug列表，验证数组长度 @2
- 步骤2：验证第一个Bug状态为active第1条的status属性 @active
- 步骤3：验证第一个Bug标题第1条的title属性 @Test bug-active
- 步骤4：验证第二个Bug状态为resolved第2条的status属性 @resolved
- 步骤5：验证第二个Bug标题第2条的title属性 @Test bug-resolved

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$tutorialTest = new tutorialModelTest();

// 4. 执行测试步骤
r(count($tutorialTest->getBugsTest())) && p() && e('2');                               // 步骤1：获取Bug列表，验证数组长度
r($tutorialTest->getBugsTest()) && p('1:status') && e('active');                       // 步骤2：验证第一个Bug状态为active
r($tutorialTest->getBugsTest()) && p('1:title') && e('Test bug-active');               // 步骤3：验证第一个Bug标题
r($tutorialTest->getBugsTest()) && p('2:status') && e('resolved');                     // 步骤4：验证第二个Bug状态为resolved
r($tutorialTest->getBugsTest()) && p('2:title') && e('Test bug-resolved');             // 步骤5：验证第二个Bug标题
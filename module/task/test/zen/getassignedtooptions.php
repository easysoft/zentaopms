#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getAssignedToOptions();
timeout=0
cid=0

- 步骤1：验证single模式multiple为false第single条的multiple属性 @0
- 步骤2：验证single模式checkbox为false第single条的checkbox属性 @0
- 步骤3：验证空manageLink时single模式无工具栏第single条的toolbar属性 @0
- 步骤4：验证multiple模式multiple为true第multiple条的multiple属性 @1
- 步骤5：验证multiple模式checkbox为true第multiple条的checkbox属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 4. 执行测试步骤（必须包含至少5个测试步骤）
r($taskTest->getAssignedToOptionsTest('')) && p('single:multiple') && e('0'); // 步骤1：验证single模式multiple为false
r($taskTest->getAssignedToOptionsTest('')) && p('single:checkbox') && e('0'); // 步骤2：验证single模式checkbox为false
r($taskTest->getAssignedToOptionsTest('')) && p('single:toolbar') && e('0'); // 步骤3：验证空manageLink时single模式无工具栏
r($taskTest->getAssignedToOptionsTest('')) && p('multiple:multiple') && e('1'); // 步骤4：验证multiple模式multiple为true
r($taskTest->getAssignedToOptionsTest('')) && p('multiple:checkbox') && e('1'); // 步骤5：验证multiple模式checkbox为true
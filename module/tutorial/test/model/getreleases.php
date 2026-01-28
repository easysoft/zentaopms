#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getReleases();
timeout=0
cid=19460

- 步骤1：验证返回数组长度为1 @1
- 步骤2：验证数组键名为1 @1
- 步骤3：验证发布ID第1条的id属性 @1
- 步骤4：验证发布名称第1条的name属性 @Test release
- 步骤5：验证发布状态第1条的status属性 @wait

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 必须包含至少5个测试步骤
r(count($tutorialTest->getReleasesTest())) && p() && e(1); // 步骤1：验证返回数组长度为1
r(array_keys($tutorialTest->getReleasesTest())) && p('0') && e('1'); // 步骤2：验证数组键名为1
r($tutorialTest->getReleasesTest()) && p('1:id') && e('1'); // 步骤3：验证发布ID
r($tutorialTest->getReleasesTest()) && p('1:name') && e('Test release'); // 步骤4：验证发布名称
r($tutorialTest->getReleasesTest()) && p('1:status') && e('wait'); // 步骤5：验证发布状态
#!/usr/bin/env php
<?php

/**

title=测试 projectTao::buildLinkForBug();
timeout=0
cid=17887

- 步骤1:测试create方法 @/zentaopms/bug-create-0-0-projectID=%s.html
- 步骤2:测试edit方法 @/zentaopms/project-bug-projectID=%s.html
- 步骤3:测试空字符串 @0
- 步骤4:测试未定义的方法browse @0
- 步骤5:测试未定义的方法view @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$projectTest = new projectTaoTest();

// 4. 强制要求:必须包含至少5个测试步骤
r($projectTest->buildLinkForBugTest('create')) && p() && e('/zentaopms/bug-create-0-0-projectID=%s.html'); // 步骤1:测试create方法
r($projectTest->buildLinkForBugTest('edit')) && p() && e('/zentaopms/project-bug-projectID=%s.html'); // 步骤2:测试edit方法
r($projectTest->buildLinkForBugTest('')) && p() && e('0'); // 步骤3:测试空字符串
r($projectTest->buildLinkForBugTest('browse')) && p() && e('0'); // 步骤4:测试未定义的方法browse
r($projectTest->buildLinkForBugTest('view')) && p() && e('0'); // 步骤5:测试未定义的方法view
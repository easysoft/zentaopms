#!/usr/bin/env php
<?php

/**

title=测试 projectTao::buildLinkForStory();
timeout=0
cid=17889

- 步骤1:测试change方法 @/zentaopms/projectstory-story-projectID=%s.html
- 步骤2:测试create方法 @/zentaopms/projectstory-story-projectID=%s.html
- 步骤3:测试zerocase方法 @/zentaopms/project-testcase-projectID=%s.html
- 步骤4:测试空字符串 @0
- 步骤5:测试未定义的方法browse @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$projectTest = new projectTest();

// 4. 强制要求:必须包含至少5个测试步骤
r($projectTest->buildLinkForStoryTest('change')) && p() && e('/zentaopms/projectstory-story-projectID=%s.html'); // 步骤1:测试change方法
r($projectTest->buildLinkForStoryTest('create')) && p() && e('/zentaopms/projectstory-story-projectID=%s.html'); // 步骤2:测试create方法
r($projectTest->buildLinkForStoryTest('zerocase')) && p() && e('/zentaopms/project-testcase-projectID=%s.html'); // 步骤3:测试zerocase方法
r($projectTest->buildLinkForStoryTest('')) && p() && e('0'); // 步骤4:测试空字符串
r($projectTest->buildLinkForStoryTest('browse')) && p() && e('0'); // 步骤5:测试未定义的方法browse
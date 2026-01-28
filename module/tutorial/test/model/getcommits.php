#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getCommits();
timeout=0
cid=19417

- 步骤1：验证返回数组长度为1 @1
- 步骤2：验证提交记录ID第bedeaaf39ef7084b9a455b9d9dba71e2db357201条的id属性 @1
- 步骤3：验证代码库ID第bedeaaf39ef7084b9a455b9d9dba71e2db357201条的repo属性 @1
- 步骤4：验证提交者第bedeaaf39ef7084b9a455b9d9dba71e2db357201条的committer属性 @admin
- 步骤5：验证提交注释第bedeaaf39ef7084b9a455b9d9dba71e2db357201条的comment属性 @Git comment.

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getCommitsTest())) && p() && e('1'); // 步骤1：验证返回数组长度为1
r($tutorialTest->getCommitsTest()) && p('bedeaaf39ef7084b9a455b9d9dba71e2db357201:id') && e('1'); // 步骤2：验证提交记录ID
r($tutorialTest->getCommitsTest()) && p('bedeaaf39ef7084b9a455b9d9dba71e2db357201:repo') && e('1'); // 步骤3：验证代码库ID
r($tutorialTest->getCommitsTest()) && p('bedeaaf39ef7084b9a455b9d9dba71e2db357201:committer') && e('admin'); // 步骤4：验证提交者
r($tutorialTest->getCommitsTest()) && p('bedeaaf39ef7084b9a455b9d9dba71e2db357201:comment') && e('Git comment.'); // 步骤5：验证提交注释
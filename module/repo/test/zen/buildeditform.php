#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildEditForm();
timeout=0
cid=0

- 步骤1：正常情况第repo条的name属性 @723test
- 步骤2：不存在版本库 @0
- 步骤3：Git类型版本库第repo条的SCM属性 @Gitlab
- 步骤4：版本库ID验证第repo条的id属性 @1
- 步骤5：对象ID验证属性objectID @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('repo');
$table->loadYaml('repo_buildeditform', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoZenTest = new repoZenTest();

// 5. 测试步骤
r($repoZenTest->buildEditFormTest(1, 1)) && p('repo:name') && e('723test'); // 步骤1：正常情况
r($repoZenTest->buildEditFormTest(999, 1)) && p() && e(0); // 步骤2：不存在版本库
r($repoZenTest->buildEditFormTest(1, 1)) && p('repo:SCM') && e('Gitlab'); // 步骤3：Git类型版本库
r($repoZenTest->buildEditFormTest(1, 1)) && p('repo:id') && e(1); // 步骤4：版本库ID验证
r($repoZenTest->buildEditFormTest(1, 1)) && p('objectID') && e(1); // 步骤5：对象ID验证
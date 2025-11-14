#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCommitsByObject();
timeout=0
cid=18053

- 步骤1：获取任务关联提交信息第0条的id属性 @1
- 步骤2：获取bug关联提交信息第0条的revision属性 @c808480afe22d3a55d94e91c59a8f3170212ade0
- 步骤3：获取需求关联提交信息第0条的comment属性 @代码注释
- 步骤4：测试不存在对象ID @0
- 步骤5：测试无效对象类型 @0
- 步骤6：测试边界值ID为0 @0
- 步骤7：测试负数ID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. zendata数据准备
zenData('task')->gen(10);
zenData('bug')->gen(10);
zenData('story')->gen(10);
zenData('relation')->loadYaml('relation')->gen(3);
zenData('repo')->loadYaml('repo')->gen(4);
zenData('repohistory')->loadYaml('repohistory')->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoTest();

// 5. 执行测试步骤（至少5个）
r($repoTest->getCommitsByObjectTest(8, 'task'))    && p('0:id')       && e('1');                  // 步骤1：获取任务关联提交信息
r($repoTest->getCommitsByObjectTest(4, 'bug'))     && p('0:revision') && e('c808480afe22d3a55d94e91c59a8f3170212ade0'); // 步骤2：获取bug关联提交信息
r($repoTest->getCommitsByObjectTest(10, 'story'))  && p('0:comment')  && e('代码注释');             // 步骤3：获取需求关联提交信息
r($repoTest->getCommitsByObjectTest(999, 'task'))  && p()             && e('0');                   // 步骤4：测试不存在对象ID
r($repoTest->getCommitsByObjectTest(1, 'invalid')) && p()             && e('0');                   // 步骤5：测试无效对象类型
r($repoTest->getCommitsByObjectTest(0, 'task'))    && p()             && e('0');                   // 步骤6：测试边界值ID为0
r($repoTest->getCommitsByObjectTest(-1, 'bug'))    && p()             && e('0');                   // 步骤7：测试负数ID
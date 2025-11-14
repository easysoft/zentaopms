#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getGiteaGroups();
timeout=0
cid=18058

- 步骤1：验证返回值是数组类型 @1
- 步骤2：有效giteaID(4)查询，期望返回空数组 @0
- 步骤3：有效giteaID(1)查询，期望返回空数组 @0
- 步骤4：无效giteaID(0)查询，期望返回空数组 @0
- 步骤5：不存在giteaID(999)查询，期望返回空数组 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. 准备测试数据
$table = zenData('pipeline');
$table->id->range('1-5');
$table->type->range('gitea{5}');
$table->name->range('gitea-test1,gitea-test2,gitea-test3,gitea-test4,gitea-test5');
$table->url->range('http://gitea1.test,http://gitea2.test,http://gitea3.test,http://gitea4.test,http://gitea5.test');
$table->token->range('token1,token2,token3,token4,token5');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoTest();

// 5. 执行测试步骤
r(is_array($repoTest->getGiteaGroupsTest(4))) && p() && e('1'); // 步骤1：验证返回值是数组类型
r(count($repoTest->getGiteaGroupsTest(4))) && p() && e('0'); // 步骤2：有效giteaID(4)查询，期望返回空数组
r(count($repoTest->getGiteaGroupsTest(1))) && p() && e('0'); // 步骤3：有效giteaID(1)查询，期望返回空数组
r(count($repoTest->getGiteaGroupsTest(0))) && p() && e('0'); // 步骤4：无效giteaID(0)查询，期望返回空数组
r(count($repoTest->getGiteaGroupsTest(999))) && p() && e('0'); // 步骤5：不存在giteaID(999)查询，期望返回空数组
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
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$repoTest = new repoModelTest();

// 4. 执行测试步骤
r($repoTest->getGiteaGroupsIsArrayTest(4)) && p() && e('1'); // 步骤1：验证返回值是数组类型
r($repoTest->getGiteaGroupsCountTest(4)) && p() && e('0'); // 步骤2：有效giteaID(4)查询，期望返回空数组
r($repoTest->getGiteaGroupsCountTest(1)) && p() && e('0'); // 步骤3：有效giteaID(1)查询，期望返回空数组
r($repoTest->getGiteaGroupsCountTest(0)) && p() && e('0'); // 步骤4：无效giteaID(0)查询，期望返回空数组
r($repoTest->getGiteaGroupsCountTest(999)) && p() && e('0'); // 步骤5：不存在giteaID(999)查询，期望返回空数组

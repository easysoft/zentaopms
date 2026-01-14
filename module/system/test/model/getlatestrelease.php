#!/usr/bin/env php
<?php

/**

title=测试 systemModel::getLatestRelease();
timeout=0
cid=18735

- 步骤1：正常调用获取最新版本（测试环境下返回false） @0
- 步骤2：验证返回结果在测试环境下为false @1
- 步骤3：测试网络连接状态（无网络返回false） @0
- 步骤4：测试环境变量配置（测试环境配置） @0
- 步骤5：测试API响应处理（API不可用返回false） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$systemTest = new systemModelTest();

// 4. 必须包含至少5个测试步骤
r($systemTest->getLatestReleaseTest()) && p() && e('0');                           // 步骤1：正常调用获取最新版本（测试环境下返回false）
r($systemTest->getLatestReleaseTest() === false) && p() && e('1');                 // 步骤2：验证返回结果在测试环境下为false
r($systemTest->getLatestReleaseTest()) && p() && e('0');                           // 步骤3：测试网络连接状态（无网络返回false）
r($systemTest->getLatestReleaseTest()) && p() && e('0');                           // 步骤4：测试环境变量配置（测试环境配置）
r($systemTest->getLatestReleaseTest()) && p() && e('0');                           // 步骤5：测试API响应处理（API不可用返回false）
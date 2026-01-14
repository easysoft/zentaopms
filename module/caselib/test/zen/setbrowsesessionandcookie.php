#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::setBrowseSessionAndCookie();
timeout=0
cid=15561

- 步骤1：正常情况执行成功 @1
- 步骤2：bymodule类型执行成功 @1
- 步骤3：不同库ID执行成功 @1
- 步骤4：默认参数执行成功 @1
- 步骤5：搜索类型执行成功 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($caselibTest->setBrowseSessionAndCookieTest(101, 'all', 0)) && p() && e('1'); // 步骤1：正常情况执行成功
r($caselibTest->setBrowseSessionAndCookieTest(102, 'bymodule', 201)) && p() && e('1'); // 步骤2：bymodule类型执行成功
r($caselibTest->setBrowseSessionAndCookieTest(103, 'all', 0)) && p() && e('1'); // 步骤3：不同库ID执行成功
r($caselibTest->setBrowseSessionAndCookieTest(0, '', 0)) && p() && e('1'); // 步骤4：默认参数执行成功
r($caselibTest->setBrowseSessionAndCookieTest(105, 'search', 0)) && p() && e('1'); // 步骤5：搜索类型执行成功
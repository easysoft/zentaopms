#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::locateNotifyLink();
timeout=0
cid=0

- 步骤1：GET请求检测逻辑 @1
- 步骤2：使用requestType参数检测 @1
- 步骤3：PATH_INFO检测逻辑 @1
- 步骤4：GET URL解析 @index.php?m=user&f=profile
- 步骤5：PATH_INFO URL解析 @user-profile.html

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sso.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$ssoTest = new ssoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($ssoTest->locateNotifyLinkTest('user/profile&id=1', 'detect_get')) && p() && e('1'); // 步骤1：GET请求检测逻辑
r($ssoTest->locateNotifyLinkTest('user/profile&id=1', 'detect_get_with_requesttype')) && p() && e('1'); // 步骤2：使用requestType参数检测
r($ssoTest->locateNotifyLinkTest('user-profile.html', 'detect_pathinfo')) && p() && e('1'); // 步骤3：PATH_INFO检测逻辑
r($ssoTest->locateNotifyLinkTest('user-profile.html', 'get_url_parsing')) && p() && e('index.php?m=user&f=profile'); // 步骤4：GET URL解析
r($ssoTest->locateNotifyLinkTest('index.php?m=user&f=profile&id=1', 'pathinfo_url_parsing')) && p() && e('user-profile.html'); // 步骤5：PATH_INFO URL解析
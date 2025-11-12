#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildLocationByGET();
timeout=0
cid=0

- 步骤1:测试已包含&的GET格式URL包含token @1
- 步骤2:测试PATH_INFO格式URL转换为GET格式 @1
- 步骤3:测试URL末尾有&符号包含referer @1
- 步骤4:测试空referer参数转换为GET格式 @1
- 步骤5:测试包含特殊字符的referer正确编码 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 设置SSO配置
global $config;
if(!isset($config->sso))
{
    $config->sso = new stdClass();
}
$config->sso->code = 'test_sso_code';
$config->sso->key  = 'test_sso_key';

// 3. 设置必要的GET参数模拟SSO环境
$_GET['token'] = 'test_token_abc123';

// 4. 用户登录(选择合适角色)
su('admin');

// 5. 创建测试实例(变量名与模块名一致)
$ssoTest = new ssoZenTest();

// 6. 强制要求:必须包含至少5个测试步骤
r(substr_count($ssoTest->buildLocationByGETTest('http://test.com/index.php?m=user&f=login', 'home'), 'token=test_token_abc123')) && p() && e('1');                        // 步骤1:测试已包含&的GET格式URL包含token
r(substr_count($ssoTest->buildLocationByGETTest('http://test.com/user-login.html', 'dashboard'), 'index.php?m=user&f=login')) && p() && e('1');                           // 步骤2:测试PATH_INFO格式URL转换为GET格式
r(substr_count($ssoTest->buildLocationByGETTest('http://test.com/index.php?m=user&f=login&', 'page'), 'referer=page')) && p() && e('1');                                   // 步骤3:测试URL末尾有&符号包含referer
r(substr_count($ssoTest->buildLocationByGETTest('http://test.com/user-profile.html', ''), 'm=user&f=profile')) && p() && e('1');                                           // 步骤4:测试空referer参数转换为GET格式
r(substr_count($ssoTest->buildLocationByGETTest('http://test.com/task-view.html', 'test%20page'), 'referer=test%20page')) && p() && e('1');                                // 步骤5:测试包含特殊字符的referer正确编码
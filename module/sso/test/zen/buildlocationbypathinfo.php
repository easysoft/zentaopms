#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildLocationByPATHINFO();
timeout=0
cid=0

- 步骤1:测试包含&的GET格式URL转换为PATH_INFO格式 @1
- 步骤2:测试已经是PATH_INFO格式的URL添加SSO参数 @1
- 步骤3:测试URL末尾有?符号包含token参数 @1
- 步骤4:测试空referer参数转换为PATH_INFO格式 @1
- 步骤5:测试包含特殊字符的referer正确编码到SSO参数中 @1

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
r(substr_count($ssoTest->buildLocationByPATHINFOTest('http://test.com/index.php?m=user&f=login', 'home'), 'user-login.html')) && p() && e('1');                        // 步骤1:测试包含&的GET格式URL转换为PATH_INFO格式
r(substr_count($ssoTest->buildLocationByPATHINFOTest('http://test.com/task-view.html', 'dashboard'), 'token=test_token_abc123')) && p() && e('1');                   // 步骤2:测试已经是PATH_INFO格式的URL添加SSO参数
r(substr_count($ssoTest->buildLocationByPATHINFOTest('http://test.com/index.php?m=project&f=browse', 'page'), 'project-browse.html')) && p() && e('1');             // 步骤3:测试URL末尾有?符号包含token参数
r(substr_count($ssoTest->buildLocationByPATHINFOTest('http://test.com/index.php?m=bug&f=create', ''), 'bug-create.html')) && p() && e('1');                          // 步骤4:测试空referer参数转换为PATH_INFO格式
r(substr_count($ssoTest->buildLocationByPATHINFOTest('http://test.com/story-create.html', 'test%20page'), 'referer=test%20page')) && p() && e('1');                  // 步骤5:测试包含特殊字符的referer正确编码到SSO参数中
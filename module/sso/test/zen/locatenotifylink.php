#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::locateNotifyLink();
timeout=0
cid=18417

- 执行locateNotifyLinkTest('http://example.com/index.php?m=user&f=view', 'home'), 'token=test_token_abc123') && substr_count($ssoTest模块的locateNotifyLinkTest方法，参数是'http://example.com/index.php?m=user&f=view', 'home'), 'referer=home'  @1
- 执行locateNotifyLinkTest('http://example.com/user-view-1.html', 'dashboard'), 'token=test_token_abc123') && substr_count($ssoTest模块的locateNotifyLinkTest方法，参数是'http://example.com/user-view-1.html', 'dashboard'), 'referer=dashboard'  @1
- 执行ssoTest模块的locateNotifyLinkTest方法，参数是'http://example.com/project-view.html', 'projects'), 'index.php'  @1
- 执行ssoTest模块的locateNotifyLinkTest方法，参数是'http://example.com/index.php?m=task&f=create', 'tasks'), '.html'  @1
- 执行ssoTest模块的locateNotifyLinkTest方法，参数是'http://example.com/bug-browse.html', 'bugs'), 'sid=test_session_123'  @1

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

// 步骤1:测试GET请求类型,location包含&符号时正确构建路径
unset($_GET['requestType']);
unset($_GET['sessionid']);
r(substr_count($ssoTest->locateNotifyLinkTest('http://example.com/index.php?m=user&f=view', 'home'), 'token=test_token_abc123') && substr_count($ssoTest->locateNotifyLinkTest('http://example.com/index.php?m=user&f=view', 'home'), 'referer=home')) && p() && e('1');

// 步骤2:测试PATH_INFO请求类型,location不包含&符号时正确构建路径
unset($_GET['requestType']);
unset($_GET['sessionid']);
r(substr_count($ssoTest->locateNotifyLinkTest('http://example.com/user-view-1.html', 'dashboard'), 'token=test_token_abc123') && substr_count($ssoTest->locateNotifyLinkTest('http://example.com/user-view-1.html', 'dashboard'), 'referer=dashboard')) && p() && e('1');

// 步骤3:测试通过requestType=GET参数强制使用GET模式
$_GET['requestType'] = 'GET';
unset($_GET['sessionid']);
r(substr_count($ssoTest->locateNotifyLinkTest('http://example.com/project-view.html', 'projects'), 'index.php')) && p() && e('1');

// 步骤4:测试通过requestType参数强制使用PATH_INFO模式(非GET值)
$_GET['requestType'] = 'PATH_INFO';
unset($_GET['sessionid']);
r(substr_count($ssoTest->locateNotifyLinkTest('http://example.com/index.php?m=task&f=create', 'tasks'), '.html')) && p() && e('1');

// 步骤5:测试包含sessionid参数时正确添加session信息到location
unset($_GET['requestType']);
$sessionData = json_encode(array('session_name' => 'sid', 'session_id' => 'test_session_123'));
$_GET['sessionid'] = base64_encode($sessionData);
r(substr_count($ssoTest->locateNotifyLinkTest('http://example.com/bug-browse.html', 'bugs'), 'sid=test_session_123')) && p() && e('1');
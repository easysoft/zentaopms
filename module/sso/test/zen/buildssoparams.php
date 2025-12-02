#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildSSOParams();
timeout=0
cid=18410

- 步骤1:测试正常referer参数生成SSO参数字符串 @1
- 步骤2:测试空referer参数生成SSO参数字符串 @1
- 步骤3:测试包含特殊字符的referer参数正确包含在结果中 @1
- 步骤4:测试生成的参数包含auth计算值 @1
- 步骤5:测试生成的参数包含callback回调地址 @1

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
r(substr_count($ssoTest->buildSSOParamsTest('home'), 'token=') && substr_count($ssoTest->buildSSOParamsTest('home'), 'auth=') && substr_count($ssoTest->buildSSOParamsTest('home'), 'userIP=') && substr_count($ssoTest->buildSSOParamsTest('home'), 'callback=') && substr_count($ssoTest->buildSSOParamsTest('home'), 'referer=')) && p() && e('1'); // 步骤1:测试正常referer参数生成SSO参数字符串
r(substr_count($ssoTest->buildSSOParamsTest(''), 'token=test_token_abc123')) && p() && e('1'); // 步骤2:测试空referer参数生成SSO参数字符串
r(substr_count($ssoTest->buildSSOParamsTest('test%20page'), 'referer=test%20page')) && p() && e('1'); // 步骤3:测试包含特殊字符的referer参数正确包含在结果中
r(substr_count($ssoTest->buildSSOParamsTest('dashboard'), 'auth=')) && p() && e('1'); // 步骤4:测试生成的参数包含auth计算值
r(substr_count($ssoTest->buildSSOParamsTest('project'), 'callback=')) && p() && e('1'); // 步骤5:测试生成的参数包含callback回调地址
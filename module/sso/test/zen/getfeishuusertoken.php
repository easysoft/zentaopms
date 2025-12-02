#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::getFeishuUserToken();
timeout=0
cid=18415

- 步骤1:测试有效的code和accessToken参数,返回失败(因为无效的凭证)属性result @fail
- 步骤2:测试空code参数,返回失败信息属性result @fail
- 步骤3:测试空accessToken参数,返回失败信息属性result @fail
- 步骤4:测试无效的code格式,返回失败信息属性result @fail
- 步骤5:测试无效的accessToken格式,返回失败信息属性result @fail

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
$config->sso->feishuTokenAPI = 'https://open.feishu.cn/open-apis/authen/v1/access_token';

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$ssoTest = new ssoZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
// 注意:由于该方法依赖外部HTTP API调用,测试将依赖真实的飞书API响应
// 使用无效凭证测试,预期都会返回fail

r($ssoTest->getFeishuUserTokenTest('test_code_001', 'test_token_001')) && p('result') && e('fail'); // 步骤1:测试有效的code和accessToken参数,返回失败(因为无效的凭证)
r($ssoTest->getFeishuUserTokenTest('', 'test_token_002')) && p('result') && e('fail'); // 步骤2:测试空code参数,返回失败信息
r($ssoTest->getFeishuUserTokenTest('test_code_003', '')) && p('result') && e('fail'); // 步骤3:测试空accessToken参数,返回失败信息
r($ssoTest->getFeishuUserTokenTest('invalid_code_123', 'invalid_token_456')) && p('result') && e('fail'); // 步骤4:测试无效的code格式,返回失败信息
r($ssoTest->getFeishuUserTokenTest('another_invalid_code', 'another_invalid_token')) && p('result') && e('fail'); // 步骤5:测试无效的accessToken格式,返回失败信息
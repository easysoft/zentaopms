#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::getFeishuAccessToken();
timeout=0
cid=0

- 步骤1:测试有效的appConfig配置,返回失败(因为无效的凭证)属性result @fail
- 步骤2:测试空appId的配置,返回失败信息属性result @fail
- 步骤3:测试空appSecret的配置,返回失败信息属性result @fail
- 步骤4:测试另一组无效凭证,返回失败信息属性result @fail
- 步骤5:测试第三组无效凭证,返回失败信息属性result @fail

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
$config->sso->feishuAppInfoAPI = 'https://open.feishu.cn/open-apis/auth/v3/app_access_token/internal';

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$ssoTest = new ssoZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
// 注意:由于该方法依赖外部HTTP API调用,测试将依赖真实的飞书API响应
// 使用无效凭证测试,预期都会返回fail

// 创建测试用的appConfig对象
$appConfig1 = new stdClass();
$appConfig1->appId = 'test_app_id_001';
$appConfig1->appSecret = 'test_app_secret_001';

$appConfig2 = new stdClass();
$appConfig2->appId = '';
$appConfig2->appSecret = 'test_app_secret_002';

$appConfig3 = new stdClass();
$appConfig3->appId = 'test_app_id_003';
$appConfig3->appSecret = '';

$appConfig4 = new stdClass();
$appConfig4->appId = 'invalid_app_id_123';
$appConfig4->appSecret = 'invalid_app_secret_456';

$appConfig5 = new stdClass();
$appConfig5->appId = 'another_invalid_id';
$appConfig5->appSecret = 'another_invalid_secret';

r($ssoTest->getFeishuAccessTokenTest($appConfig1)) && p('result') && e('fail'); // 步骤1:测试有效的appConfig配置,返回失败(因为无效的凭证)
r($ssoTest->getFeishuAccessTokenTest($appConfig2)) && p('result') && e('fail'); // 步骤2:测试空appId的配置,返回失败信息
r($ssoTest->getFeishuAccessTokenTest($appConfig3)) && p('result') && e('fail'); // 步骤3:测试空appSecret的配置,返回失败信息
r($ssoTest->getFeishuAccessTokenTest($appConfig4)) && p('result') && e('fail'); // 步骤4:测试另一组无效凭证,返回失败信息
r($ssoTest->getFeishuAccessTokenTest($appConfig5)) && p('result') && e('fail'); // 步骤5:测试第三组无效凭证,返回失败信息
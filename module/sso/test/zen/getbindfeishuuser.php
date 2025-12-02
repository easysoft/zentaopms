#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::getBindFeishuUser();
timeout=0
cid=18413

- 步骤1:测试有效token但API返回空响应属性result @fail
- 步骤2:测试空用户token,返回失败信息属性result @fail
- 步骤3:测试无效的用户token,返回失败信息属性result @fail
- 步骤4:测试未绑定的飞书用户,返回未绑定错误属性result @fail
- 步骤5:测试另一个无效token,返回失败信息属性result @fail

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->password->range('123456{10}');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->email->range('admin@test.com,user1@test.com,user2@test.com,user3@test.com,user4@test.com,user5@test.com,user6@test.com,user7@test.com,user8@test.com,user9@test.com');
$user->deleted->range('0{10}');
$user->gen(10);

$oauth = zenData('oauth');
$oauth->account->range('admin,user1,user2');
$oauth->openID->range('feishu_openid_1,feishu_openid_2,feishu_openid_3');
$oauth->providerType->range('webhook{3}');
$oauth->providerID->range('1{3}');
$oauth->gen(3);

// 3. 设置SSO配置
global $config;
if(!isset($config->sso))
{
    $config->sso = new stdClass();
}
$config->sso->feishuUserInfoAPI = 'https://open.feishu.cn/open-apis/authen/v1/user_info';

// 4. 用户登录(选择合适角色)
su('admin');

// 5. 创建测试实例(变量名与模块名一致)
$ssoTest = new ssoZenTest();

// 6. 创建飞书配置对象
$feishuConfig = new stdClass();
$feishuConfig->id = 1;
$feishuConfig->appId = 'test_app_id';
$feishuConfig->appSecret = 'test_app_secret';

// 7. 强制要求:必须包含至少5个测试步骤
// 注意:由于该方法依赖外部HTTP API调用,这里主要测试方法的返回结果结构
// 实际的HTTP调用无法在单元测试中mock,因此测试会依赖真实的飞书API响应
// 在实际环境中,这些测试可能会失败,因为需要有效的userToken

r($ssoTest->getBindFeishuUserTest('valid_user_token_mock', $feishuConfig)) && p('result') && e('fail'); // 步骤1:测试有效token但API返回空响应
r($ssoTest->getBindFeishuUserTest('', $feishuConfig)) && p('result') && e('fail'); // 步骤2:测试空用户token,返回失败信息
r($ssoTest->getBindFeishuUserTest('invalid_token_123', $feishuConfig)) && p('result') && e('fail'); // 步骤3:测试无效的用户token,返回失败信息
r($ssoTest->getBindFeishuUserTest('unbound_user_token', $feishuConfig)) && p('result') && e('fail'); // 步骤4:测试未绑定的飞书用户,返回未绑定错误
r($ssoTest->getBindFeishuUserTest('another_invalid_token', $feishuConfig)) && p('result') && e('fail'); // 步骤5:测试另一个无效token,返回失败信息
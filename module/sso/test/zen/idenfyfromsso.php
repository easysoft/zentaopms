#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::idenfyFromSSO();
timeout=0
cid=0

- 执行ssoTest模块的idenfyFromSSOTest方法，参数是'/index.php'  @0
- 执行ssoTest模块的idenfyFromSSOTest方法，参数是'/index.php'  @0
- 执行ssoTest模块的idenfyFromSSOTest方法，参数是'/index.php'  @0
- 执行ssoTest模块的idenfyFromSSOTest方法，参数是'/index.php'  @0
- 执行ssoTest模块的idenfyFromSSOTest方法，参数是'/index.php'  @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zendata('user')->loadYaml('user_identifyfromsso', false, 2)->gen(10);

// 3. 设置SSO配置
global $config;
if(!isset($config->sso))
{
    $config->sso = new stdClass();
}
$config->sso->code = 'test_sso_code';
$config->sso->key  = 'test_sso_key';

// 4. 用户登录(选择合适角色)
su('admin');

// 5. 创建测试实例(变量名与模块名一致)
$ssoTest = new ssoZenTest();

// 6. 准备测试数据
$testToken = 'test_token_123';
$testData  = new stdClass();
$testData->token   = $testToken;
$testData->account = 'admin';
$testData->auth    = md5($config->sso->code . helper::getRemoteIp() . $testToken . $config->sso->key);

$validData = base64_encode(json_encode($testData));
$validMd5  = md5($validData);

// 7. 测试步骤:必须包含至少5个测试步骤

// 步骤1:测试status参数不为success时返回false
$_GET['status'] = 'fail';
$_GET['data']   = $validData;
$_GET['md5']    = $validMd5;
r($ssoTest->idenfyFromSSOTest('/index.php')) && p() && e('0');

// 步骤2:测试md5签名验证失败时返回false
$_GET['status'] = 'success';
$_GET['data']   = $validData;
$_GET['md5']    = 'invalid_md5';
r($ssoTest->idenfyFromSSOTest('/index.php')) && p() && e('0');

// 步骤3:测试auth验证失败时返回false
$_GET['status'] = 'success';
$testDataInvalidAuth = clone $testData;
$testDataInvalidAuth->auth = 'invalid_auth';
$invalidAuthData = base64_encode(json_encode($testDataInvalidAuth));
$_GET['data']   = $invalidAuthData;
$_GET['md5']    = md5($invalidAuthData);
r($ssoTest->idenfyFromSSOTest('/index.php')) && p() && e('0');

// 步骤4:测试不同token生成不同auth导致验证失败返回false
$_GET['status'] = 'success';
$testDataDiffToken = clone $testData;
$testDataDiffToken->token = 'different_token';
$testDataDiffToken->auth  = md5($config->sso->code . helper::getRemoteIp() . $testData->token . $config->sso->key);
$diffTokenData = base64_encode(json_encode($testDataDiffToken));
$_GET['data']   = $diffTokenData;
$_GET['md5']    = md5($diffTokenData);
r($ssoTest->idenfyFromSSOTest('/index.php')) && p() && e('0');

// 步骤5:测试空data内容时返回false
$_GET['status'] = 'success';
$emptyData = base64_encode('{}');
$_GET['data']   = $emptyData;
$_GET['md5']    = 'invalid_md5';
r($ssoTest->idenfyFromSSOTest('/index.php')) && p() && e('0');
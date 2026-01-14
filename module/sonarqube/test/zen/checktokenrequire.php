#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeZen::checkTokenRequire();
timeout=0
cid=18389

- 执行sonarqubeTest模块的checkTokenRequireTest方法，参数是$validData  @success
- 执行sonarqubeTest模块的checkTokenRequireTest方法，参数是$noName 第name条的0属性 @『应用名称』不能为空。
- 执行sonarqubeTest模块的checkTokenRequireTest方法，参数是$noUrl 第url条的0属性 @『服务器地址』不能为空。
- 执行sonarqubeTest模块的checkTokenRequireTest方法，参数是$noAccount 第account条的0属性 @『用户名』不能为空。
- 执行sonarqubeTest模块的checkTokenRequireTest方法，参数是$noPassword 第password条的0属性 @『密码』不能为空。
- 执行sonarqubeTest模块的checkTokenRequireTest方法，参数是$invalidUrl 第url条的0属性 @『服务器地址』应当为合法的URL。
- 执行sonarqubeTest模块的checkTokenRequireTest方法，参数是$wrongProtocol 第url条的0属性 @无效的SonarQube服务地址。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('ajaxtest');

su('admin');

$sonarqubeTest = new sonarqubeZenTest();

$validData = new stdclass();
$validData->name = 'Test SonarQube';
$validData->url = 'http://test.sonarqube.com';
$validData->account = 'admin';
$validData->password = 'test123';
r($sonarqubeTest->checkTokenRequireTest($validData)) && p() && e('success');

$noName = new stdclass();
$noName->name = '';
$noName->url = 'http://test.sonarqube.com';
$noName->account = 'admin';
$noName->password = 'test123';
r($sonarqubeTest->checkTokenRequireTest($noName)) && p('name:0') && e('『应用名称』不能为空。');

$noUrl = new stdclass();
$noUrl->name = 'Test SonarQube';
$noUrl->url = '';
$noUrl->account = 'admin';
$noUrl->password = 'test123';
r($sonarqubeTest->checkTokenRequireTest($noUrl)) && p('url:0') && e('『服务器地址』不能为空。');

$noAccount = new stdclass();
$noAccount->name = 'Test SonarQube';
$noAccount->url = 'http://test.sonarqube.com';
$noAccount->account = '';
$noAccount->password = 'test123';
r($sonarqubeTest->checkTokenRequireTest($noAccount)) && p('account:0') && e('『用户名』不能为空。');

$noPassword = new stdclass();
$noPassword->name = 'Test SonarQube';
$noPassword->url = 'http://test.sonarqube.com';
$noPassword->account = 'admin';
$noPassword->password = '';
r($sonarqubeTest->checkTokenRequireTest($noPassword)) && p('password:0') && e('『密码』不能为空。');

$invalidUrl = new stdclass();
$invalidUrl->name = 'Test SonarQube';
$invalidUrl->url = 'invalid-url';
$invalidUrl->account = 'admin';
$invalidUrl->password = 'test123';
r($sonarqubeTest->checkTokenRequireTest($invalidUrl)) && p('url:0') && e('『服务器地址』应当为合法的URL。');

$wrongProtocol = new stdclass();
$wrongProtocol->name = 'Test SonarQube';
$wrongProtocol->url = 'ftp://test.sonarqube.com';
$wrongProtocol->account = 'admin';
$wrongProtocol->password = 'test123';
r($sonarqubeTest->checkTokenRequireTest($wrongProtocol)) && p('url:0') && e('无效的SonarQube服务地址。');
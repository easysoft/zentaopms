#!/usr/bin/env php
<?php

/**

title=测试 userZen::parseLoginModuleAndMethod();
timeout=0
cid=19677

- 执行userTest模块的parseLoginModuleAndMethodTest方法，参数是'/zentao/user-login.html'  @user
- 执行userTest模块的parseLoginModuleAndMethodTest方法，参数是'/zentao/user-login.html' 属性1 @login
- 执行userTest模块的parseLoginModuleAndMethodTest方法，参数是'/zentao/index.php?m=user&f=login'  @user
- 执行userTest模块的parseLoginModuleAndMethodTest方法，参数是'/zentao/index.php?m=user&f=login' 属性1 @login
- 执行userTest模块的parseLoginModuleAndMethodTest方法，参数是'/zentao/userlogin.html'  @
- 执行userTest模块的parseLoginModuleAndMethodTest方法，参数是'/zentao/index.php?m=user'  @
- 执行userTest模块的parseLoginModuleAndMethodTest方法，参数是''  @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userTest = new userZenTest();

// 测试步骤1：PATH_INFO模式解析正常URL模块名
global $config;
$config->requestType = 'PATH_INFO';
$config->requestFix = '-';
r($userTest->parseLoginModuleAndMethodTest('/zentao/user-login.html')) && p('0') && e('user');

// 测试步骤2：PATH_INFO模式解析正常URL方法名
r($userTest->parseLoginModuleAndMethodTest('/zentao/user-login.html')) && p('1') && e('login');

// 测试步骤3：查询参数模式解析正常URL模块名
$config->requestType = 'GET';
r($userTest->parseLoginModuleAndMethodTest('/zentao/index.php?m=user&f=login')) && p('0') && e('user');

// 测试步骤4：查询参数模式解析正常URL方法名
r($userTest->parseLoginModuleAndMethodTest('/zentao/index.php?m=user&f=login')) && p('1') && e('login');

// 测试步骤5：PATH_INFO模式解析不包含requestFix的URL
$config->requestType = 'PATH_INFO';
$config->requestFix = '-';
r($userTest->parseLoginModuleAndMethodTest('/zentao/userlogin.html')) && p('0') && e('');

// 测试步骤6：查询参数模式解析只有模块参数的URL
$config->requestType = 'GET';
r($userTest->parseLoginModuleAndMethodTest('/zentao/index.php?m=user')) && p('0') && e('');

// 测试步骤7：解析空URL的模块名
r($userTest->parseLoginModuleAndMethodTest('')) && p('0') && e('');
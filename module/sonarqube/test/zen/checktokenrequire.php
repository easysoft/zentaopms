#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeZen::checkTokenRequire();
cid=0

- 测试步骤1：正常输入情况 >> 期望正常结果
- 测试步骤2：缺少必填字段name >> 期望验证错误
- 测试步骤3：缺少必填字段url >> 期望验证错误
- 测试步骤4：无效的URL格式 >> 期望URL错误
- 测试步骤5：无效的URL协议 >> 期望主机错误

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';
su('admin');

$sonarqubeTest = new sonarqubeTest();

r($sonarqubeTest->checkTokenRequireTest((object)array('name' => 'Test Server', 'url' => 'https://sonar.example.com', 'account' => 'admin', 'password' => '123456'))) && p() && e('success'); // 步骤1：正常情况
r($sonarqubeTest->checkTokenRequireTest((object)array('url' => 'https://sonar.example.com', 'account' => 'admin', 'password' => '123456'))) && p('name:0') && e('『应用名称』不能为空。'); // 步骤2：缺少name字段
r($sonarqubeTest->checkTokenRequireTest((object)array('name' => 'Test Server', 'account' => 'admin', 'password' => '123456'))) && p('url:0') && e('『服务器地址』不能为空。'); // 步骤3：缺少url字段
r($sonarqubeTest->checkTokenRequireTest((object)array('name' => 'Test Server', 'url' => 'invalid-url', 'account' => 'admin', 'password' => '123456'))) && p('url:0') && e('『服务器地址』应当为合法的URL。'); // 步骤4：无效URL格式
r($sonarqubeTest->checkTokenRequireTest((object)array('name' => 'Test Server', 'url' => 'ftp://sonar.example.com', 'account' => 'admin', 'password' => '123456'))) && p('url:0') && e('无效的SonarQube服务地址。'); // 步骤5：无效URL协议
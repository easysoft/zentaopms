#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->callAdminAPI();
timeout=0
cid=19760

- 测试没有ZAI设置时调用管理员API @fail
- 测试缺少管理员Token时调用管理员API @fail
- 测试设置完整ZAI配置后调用管理员API @fail
- 测试管理员API的不同HTTP方法 @fail
- 测试管理员API带参数调用 @fail

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试没有ZAI设置时调用管理员API */
r($zai->callAdminAPITest('/admin/test')) && p('result') && e('fail'); // 测试没有ZAI设置时调用管理员API

/* 设置不完整的ZAI配置（没有管理员Token） */
$incompleteSettings = new stdClass();
$incompleteSettings->host = 'testhost.com';
$incompleteSettings->port = 8080;
$incompleteSettings->appID = 'testappid123';
$incompleteSettings->token = 'testtoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($incompleteSettings));

/* 测试没有管理员Token时调用管理员API */
r($zai->callAdminAPITest('/admin/test')) && p('result') && e('fail'); // 测试缺少管理员Token时调用管理员API

/* 设置完整的ZAI配置（包含管理员Token） */
$completeSetting = new stdClass();
$completeSetting->host = 'testhost.com';
$completeSetting->port = 8080;
$completeSetting->appID = 'testappid123';
$completeSetting->token = 'testtoken123';
$completeSetting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($completeSetting));

/* 测试设置完整ZAI配置后调用管理员API */
r($zai->callAdminAPITest('/v8/admin/test', 'GET')) && p('result') && e('fail'); // 测试设置完整ZAI配置后调用管理员API

/* 测试管理员API的不同HTTP方法 */
r($zai->callAdminAPITest('/v8/admin/users', 'POST')) && p('result') && e('fail'); // 测试管理员API的不同HTTP方法

/* 测试管理员API带参数调用 */
$params = array('page' => 1, 'limit' => 10);
$postData = array('name' => 'test', 'description' => 'admin test');
r($zai->callAdminAPITest('/v8/admin/create', 'POST', $params, $postData)) && p('result') && e('fail'); // 测试管理员API带参数调用

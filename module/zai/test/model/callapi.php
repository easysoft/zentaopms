#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->callAPI() and callAdminAPI();
timeout=0
cid=19761

- 测试没有ZAI设置时调用API @fail
- 测试设置ZAI配置后调用普通API @fail
- 测试调用不同HTTP方法的API @fail
- 测试调用带参数的API @fail
- 测试调用管理员API @fail

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试没有ZAI设置时调用API */
r($zai->callAPITest('/test/path', 'GET')) && p('result') && e('fail'); // 测试没有ZAI设置时调用API

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 测试设置ZAI配置后调用普通API */
r($zai->callAPITest('/v1/test', 'GET')) && p('result') && e('fail'); // 测试设置ZAI配置后调用普通API

/* 测试调用不同HTTP方法的API */
r($zai->callAPITest('/v1/test', 'PUT', null, array('key' => 'value'))) && p('result') && e('fail'); // 测试调用不同HTTP方法的API

/* 测试调用带参数的API */
$params = array('param1' => 'value1', 'param2' => 'value2');
$postData = array('data1' => 'test1', 'data2' => 'test2');
r($zai->callAPITest('/v1/test', 'POST', $params, $postData)) && p('result') && e('fail'); // 测试调用带参数的API

/* 测试调用管理员API */
r($zai->callAdminAPITest('/v1/admin/test', 'POST')) && p('result') && e('fail'); // 测试调用管理员API

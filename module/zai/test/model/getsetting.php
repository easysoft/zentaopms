#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

/**

title=测试 zaiModel->getSetting();
timeout=0
cid=19773

- 测试获取不存在的ZAI设置 @0
- 测试设置完整的ZAI配置后获取设置（不包含管理员token）
 - 属性host @testhost.com
 - 属性port @8080
 - 属性appID @testappid123
 - 属性token @testtoken123
 - 属性adminToken @~~
- 测试设置完整的ZAI配置后获取设置（包含管理员token）
 - 属性host @testhost.com
 - 属性port @8080
 - 属性appID @testappid123
 - 属性token @testtoken123
 - 属性adminToken @testadmintoken123

*/

zenData('config')->gen(0);

su('admin');

global $tester;
$zai = new zaiTest();

/* 测试获取不存在的ZAI设置 */
r($zai->getSettingTest()) && p() && e('0'); // 测试获取不存在的ZAI设置

/* 设置完整的ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$setting->zaiTokenTTL = 1200;

$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

r($zai->getSettingTest()) && p('host,port,appID,token,adminToken') && e('testhost.com,8080,testappid123,testtoken123,~~'); // 测试设置完整的ZAI配置后获取设置（不包含管理员token）

r($zai->getSettingTest(true)) && p('host,port,appID,token,adminToken') && e('testhost.com,8080,testappid123,testtoken123,testadmintoken123'); // 测试设置完整的ZAI配置后获取设置（包含管理员token）
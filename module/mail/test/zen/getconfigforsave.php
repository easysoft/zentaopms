#!/usr/bin/env php
<?php

/**

title=测试 mailZen::getConfigForSave();
timeout=0
cid=17040

- 执行mailTest模块的getConfigForSaveZenTest方法 属性turnon @1
- 执行mailTest模块的getConfigForSaveZenTest方法 属性mta @smtp
- 执行mailTest模块的getConfigForSaveZenTest方法 属性async @1
- 执行$result->smtp) ? 1 : 0 @1
- 执行$result) && isset($result->turnon) && isset($result->mta) && is_object($result->smtp) ? 1 : 0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mailzen.unittest.class.php';

su('admin');

$mailTest = new mailZenTest();
global $tester;

// 测试步骤1：正常配置数据turnon
$_POST = array();
$_POST['turnon'] = '1';
$_POST['async'] = '1';
$_POST['fromAddress'] = 'test@example.com';
$_POST['fromName'] = 'Test User';
$_POST['domain'] = 'https://example.com';
$_POST['host'] = 'smtp.example.com';
$_POST['port'] = '587';
$_POST['auth'] = '1';
$_POST['username'] = 'test@example.com';
$_POST['password'] = 'password';
$_POST['secure'] = 'tls';
$_POST['debug'] = '0';
$_POST['charset'] = 'utf-8';
$tester->post = (object)$_POST;
r($mailTest->getConfigForSaveZenTest()) && p('turnon') && e('1');

// 测试步骤2：验证mta固定为smtp
$_POST = array();
$_POST['turnon'] = '0';
$_POST['async'] = '0';
$_POST['fromAddress'] = '';
$_POST['fromName'] = '';
$_POST['domain'] = '';
$_POST['host'] = '';
$_POST['port'] = '';
$_POST['auth'] = '0';
$_POST['username'] = '';
$_POST['password'] = '';
$_POST['secure'] = '';
$_POST['debug'] = '0';
$_POST['charset'] = '';
$tester->post = (object)$_POST;
r($mailTest->getConfigForSaveZenTest()) && p('mta') && e('smtp');

// 测试步骤3：验证async属性
$_POST = array();
$_POST['turnon'] = '1';
$_POST['async'] = '1';
$_POST['fromAddress'] = 'test@example.com';
$_POST['fromName'] = 'Test User';
$_POST['domain'] = 'https://example.com';
$_POST['host'] = 'smtp.example.com';
$_POST['port'] = '587';
$_POST['auth'] = '1';
$_POST['username'] = 'test@example.com';
$_POST['password'] = 'password';
$_POST['secure'] = 'tls';
$_POST['debug'] = '0';
$_POST['charset'] = 'utf-8';
$tester->post = (object)$_POST;
r($mailTest->getConfigForSaveZenTest()) && p('async') && e('1');

// 测试步骤4：验证smtp子对象存在
$_POST = array();
$_POST['turnon'] = '1';
$_POST['async'] = '0';
$_POST['fromAddress'] = 'user@test.com';
$_POST['fromName'] = 'Test';
$_POST['domain'] = 'https://test.com';
$_POST['host'] = 'mail.test.com';
$_POST['port'] = '465';
$_POST['auth'] = '1';
$_POST['username'] = 'user@test.com';
$_POST['password'] = 'testpass';
$_POST['secure'] = 'ssl';
$_POST['debug'] = '0';
$_POST['charset'] = 'utf-8';
$tester->post = (object)$_POST;
$result = $mailTest->getConfigForSaveZenTest();
r(is_object($result->smtp) ? 1 : 0) && p() && e(1);

// 测试步骤5：验证对象结构完整性
$_POST = array();
$_POST['turnon'] = '1';
$_POST['async'] = '0';
$_POST['fromAddress'] = 'user@test.com';
$_POST['fromName'] = 'Test';
$_POST['domain'] = 'https://test.com';
$_POST['host'] = 'mail.test.com';
$_POST['port'] = '465';
$_POST['auth'] = '1';
$_POST['username'] = 'user@test.com';
$_POST['password'] = 'testpass';
$_POST['secure'] = 'ssl';
$_POST['debug'] = '0';
$_POST['charset'] = 'utf-8';
$tester->post = (object)$_POST;
$result = $mailTest->getConfigForSaveZenTest();
r(is_object($result) && isset($result->turnon) && isset($result->mta) && is_object($result->smtp) ? 1 : 0) && p() && e(1);
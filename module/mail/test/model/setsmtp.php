#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setSMTP();
timeout=0
cid=17024

- 测试步骤1：默认SMTP配置验证
 - 属性host @localhost
 - 属性debug @0
 - 属性charset @utf-8
- 测试步骤2：修改主机地址配置属性host @127.0.0.1
- 测试步骤3：修改端口和安全配置
 - 属性port @465
 - 属性secure @ssl
- 测试步骤4：修改字符集配置属性charset @utf-8
- 测试步骤5：修改调试模式配置属性debug @2
- 测试步骤6：修改认证配置属性auth @~~
- 测试步骤7：完整配置验证
 - 属性host @smtp.gmail.com
 - 属性port @587
 - 属性secure @tls
 - 属性username @test
 - 属性auth @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mailTest = new mailModelTest();

r($mailTest->setSMTPTest()) && p('host,debug,charset') && e('localhost,0,utf-8'); // 测试步骤1：默认SMTP配置验证

$mailTest->setSMTPConfig(array('host' => '127.0.0.1'));
r($mailTest->setSMTPTest()) && p('host') && e('127.0.0.1'); // 测试步骤2：修改主机地址配置

$mailTest->setSMTPConfig(array('port' => '465', 'secure' => 'ssl'));
r($mailTest->setSMTPTest()) && p('port,secure') && e('465,ssl'); // 测试步骤3：修改端口和安全配置

$mailTest->setSMTPConfig(array('charset' => 'utf-8'));
r($mailTest->setSMTPTest()) && p('charset') && e('utf-8'); // 测试步骤4：修改字符集配置

$mailTest->setSMTPConfig(array('debug' => '2'));
r($mailTest->setSMTPTest()) && p('debug') && e('2'); // 测试步骤5：修改调试模式配置

$mailTest->setSMTPConfig(array('auth' => false));
r($mailTest->setSMTPTest()) && p('auth') && e('~~'); // 测试步骤6：修改认证配置

$mailTest->setSMTPConfig(array('host' => 'smtp.gmail.com', 'port' => '587', 'secure' => 'tls', 'username' => 'test', 'auth' => true));
r($mailTest->setSMTPTest()) && p('host,port,secure,username,auth') && e('smtp.gmail.com,587,tls,test,1'); // 测试步骤7：完整配置验证

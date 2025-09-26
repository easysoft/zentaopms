#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setMTA();
timeout=0
cid=0

- 步骤1：正常情况下的SMTP MTA设置 @PHPMailer
- 步骤2：验证单例模式实现 @1
- 步骤3：验证MTA字符编码配置属性CharSet @utf-8
- 步骤4：验证SMTP主机地址配置属性Host @localhost
- 步骤5：验证Gmail类型MTA编码设置属性CharSet @UTF-8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->getMTAClassNameTest()) && p() && e('PHPMailer'); // 步骤1：正常情况下的SMTP MTA设置
r($mailTest->testMTASingletonTest()) && p() && e('1'); // 步骤2：验证单例模式实现
r($mailTest->setMTATest()) && p('CharSet') && e('utf-8'); // 步骤3：验证MTA字符编码配置
r($mailTest->setMTATest()) && p('Host') && e('localhost'); // 步骤4：验证SMTP主机地址配置
r($mailTest->setMTAWithTypeTest('gmail')) && p('CharSet') && e('UTF-8'); // 步骤5：验证Gmail类型MTA编码设置
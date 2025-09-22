#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setMTA();
timeout=0
cid=0

- 步骤1：正常SMTP配置的MTA设置 @object
- 步骤2：验证MTA字符编码设置属性CharSet @utf-8
- 步骤3：验证SMTP主机配置属性Host @localhost
- 步骤4：验证SMTP调试设置属性SMTPDebug @0
- 步骤5：验证MTA对象类型 @PHPMailer
- 步骤6：验证单例模式 @1
- 步骤7：验证MTA实例属性设置
 - 属性CharSet @utf-8
 - 属性Host @localhost
 - 属性SMTPDebug @0
- 步骤8：验证SMTP类型MTA设置属性CharSet @utf-8
- 步骤9：验证Gmail类型MTA设置属性CharSet @UTF-8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

su('admin');

$mailTest = new mailTest();

r($mailTest->getMTATypeTest()) && p() && e('object'); // 步骤1：正常SMTP配置的MTA设置
r($mailTest->setMTATest()) && p('CharSet') && e('utf-8'); // 步骤2：验证MTA字符编码设置
r($mailTest->setMTATest()) && p('Host') && e('localhost'); // 步骤3：验证SMTP主机配置
r($mailTest->setMTATest()) && p('SMTPDebug') && e('0'); // 步骤4：验证SMTP调试设置
r($mailTest->getMTAClassNameTest()) && p() && e('PHPMailer'); // 步骤5：验证MTA对象类型
r($mailTest->testMTASingletonTest()) && p() && e('1'); // 步骤6：验证单例模式
r($mailTest->setMTATest()) && p('CharSet,Host,SMTPDebug') && e('utf-8,localhost,0'); // 步骤7：验证MTA实例属性设置
r($mailTest->setMTAWithTypeTest('smtp')) && p('CharSet') && e('utf-8'); // 步骤8：验证SMTP类型MTA设置
r($mailTest->setMTAWithTypeTest('gmail')) && p('CharSet') && e('UTF-8'); // 步骤9：验证Gmail类型MTA设置
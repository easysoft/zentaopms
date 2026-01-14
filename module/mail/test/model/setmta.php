#!/usr/bin/env php
<?php

/**

title=测试 mailModel::setMTA();
timeout=0
cid=17023

- 步骤1：验证setMTA方法基本功能和CharSet设置属性CharSet @utf-8
- 步骤2：验证单例模式实现机制 @1
- 步骤3：验证SMTP主机地址配置属性Host @localhost
- 步骤4：验证Gmail配置的CharSet设置属性CharSet @UTF-8
- 步骤5：验证自定义字符集配置属性CharSet @gbk

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$mailTest = new mailModelTest();

r($mailTest->setMTATest()) && p('CharSet') && e('utf-8'); // 步骤1：验证setMTA方法基本功能和CharSet设置
r($mailTest->mtaSingletonTest()) && p() && e('1'); // 步骤2：验证单例模式实现机制
r($mailTest->setMTATest()) && p('Host') && e('localhost'); // 步骤3：验证SMTP主机地址配置
r($mailTest->setMTAGmailTest()) && p('CharSet') && e('UTF-8'); // 步骤4：验证Gmail配置的CharSet设置
r($mailTest->setMTACharsetTest('gbk')) && p('CharSet') && e('gbk'); // 步骤5：验证自定义字符集配置
#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getConfigByDetectingSMTP();
timeout=0
cid=17006

- 测试步骤1：465端口SSL配置检测属性host @smtp.qq.com
- 测试步骤2：25端口普通配置检测属性secure @0
- 测试步骤3：用户名映射到配置属性username @testuser
- 测试步骤4：域名不存在的情况 @0
- 测试步骤5：465端口SSL安全配置属性secure @ssl
- 测试步骤6：端口号正确映射属性port @465
- 测试步骤7：认证设置验证属性auth @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';
su('admin');

$mail = new mailTest();

r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('host')     && e('smtp.qq.com'); // 测试步骤1：465端口SSL配置检测
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 25))  && p('secure')   && e('0');           // 测试步骤2：25端口普通配置检测
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('username') && e('testuser');    // 测试步骤3：用户名映射到配置
r($mail->getConfigByDetectingSMTPTest('testqq.com', 'testuser', 25))  && p()           && e('0');           // 测试步骤4：域名不存在的情况
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('secure')   && e('ssl');         // 测试步骤5：465端口SSL安全配置
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('port')     && e('465');         // 测试步骤6：端口号正确映射
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('auth')     && e('1');           // 测试步骤7：认证设置验证
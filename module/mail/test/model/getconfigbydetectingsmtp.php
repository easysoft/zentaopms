#!/usr/bin/env php
<?php

/**

title=测试 mailModel::getConfigByDetectingSMTP();
timeout=0
cid=0

- 测试465端口SSL配置检测属性host @smtp.qq.com
- 测试25端口普通配置检测属性secure @0
- 测试用户名映射到配置属性username @testuser
- 测试域名不存在的情况 @0
- 测试auth和port属性
 - 属性auth @1
 - 属性port @465

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';
su('admin');

$mail = new mailTest();

r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('host')     && e('smtp.qq.com'); //测试465端口SSL配置检测
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 25))  && p('secure')   && e('0');           //测试25端口普通配置检测
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('username') && e('testuser');    //测试用户名映射到配置
r($mail->getConfigByDetectingSMTPTest('testqq.com', 'testuser', 25))  && p()           && e('0');           //测试域名不存在的情况
r($mail->getConfigByDetectingSMTPTest('qq.com',     'testuser', 465)) && p('auth,port') && e('1,465');       //测试auth和port属性
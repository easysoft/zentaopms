#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getConfigByDetectingSMTP();
cid=0

- 根据域名获取smtp属性host @smtp.qq.com
- 根据域名获取secure属性secure @ssl
- 当域名不存在时 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

r($mail->getConfigByDetectingSMTPTest('qq.com',     '122@qq.com', 465)) && p('host')   && e('smtp.qq.com'); //根据域名获取smtp
r($mail->getConfigByDetectingSMTPTest('qq.com',     '122@qq.com', 465)) && p('secure') && e('ssl');         //根据域名获取secure
r($mail->getConfigByDetectingSMTPTest('testqq.com', '122@qq.com', 22))  && p()         && e('0');           //当域名不存在时

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->getConfigByDetectingSMTP();
cid=1
pid=1

根据域名获取smtp >> smtp.qq.com
根据域名获取secure >> ssl
当端口号不为465时获取secure >> 0
当域名不存在时 >> 0

*/

$mail = new mailTest();

$result1 = $mail->getConfigByDetectingSMTPTest('qq.com','122@qq.com',465);
$result2 = $mail->getConfigByDetectingSMTPTest('qq.com','122@qq.com',465);
$result3 = $mail->getConfigByDetectingSMTPTest('qq.com','122@qq.com',22);
$result4 = $mail->getConfigByDetectingSMTPTest('testqq.com','122@qq.com',22);

r($result1) && p('host')   && e('smtp.qq.com'); //根据域名获取smtp
r($result2) && p('secure') && e('ssl');         //根据域名获取secure
r($result3) && p('secure') && e('0');           //当端口号不为465时获取secure
r($result4) && p()         && e('0');           //当域名不存在时
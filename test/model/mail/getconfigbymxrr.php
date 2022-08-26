#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/mail.class.php';
su('admin');

/**

title=测试 mailModel->getConfigByMXRR();
cid=1
pid=1

获取qq相关信息 >> smtp.qq.com
获取sohu相关信息 >> smtp.sohu.com

*/

$mail = new mailTest();

$result1 = $mail->getConfigByMXRRTest('qq.com','test');
$result2 = $mail->getConfigByMXRRTest('sohu.com','test');

r($result1) && p('host') && e('smtp.qq.com');   //获取qq相关信息
r($result2) && p('host') && e('smtp.sohu.com'); //获取sohu相关信息
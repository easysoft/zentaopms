#!/usr/bin/env php
<?php

/**

title=测试 mailModel->getConfigFromProvider();
cid=0

- 获取qq相关信息属性host @smtp.qq.com
- 获取sohu相关信息属性host @smtp.sohu.com

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

$result1 = $mail->getConfigFromProviderTest('qq.com','123');
$result2 = $mail->getConfigFromProviderTest('sohu.com','123');

r($result1) && p('host') && e('smtp.qq.com');   //获取qq相关信息
r($result2) && p('host') && e('smtp.sohu.com'); //获取sohu相关信息

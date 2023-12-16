#!/usr/bin/env php
<?php

/**

title=测试 mailModel->autoDetect();
cid=0

- 检测qq邮箱信息属性host @smtp.qq.com
- 检测163邮箱信息属性host @smtp.163.com
- 检测搜狐邮箱信息属性host @smtp.sohu.com
- 检测搜狐vip邮箱信息属性host @smtp.vip.sohu.com
- 检测不存在的邮箱服务商信息 @没有检测到相关信息

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mail.class.php';
su('admin');

$mail = new mailTest();

$data = array('test@qq.com', 'test@163.com', 'test@sohu.com', 'test@vip.sohu.com', 'testm');

r($mail->autoDetectTest($data[0])) && p('host') && e('smtp.qq.com');        //检测qq邮箱信息
r($mail->autoDetectTest($data[1])) && p('host') && e('smtp.163.com');       //检测163邮箱信息
r($mail->autoDetectTest($data[2])) && p('host') && e('smtp.sohu.com');      //检测搜狐邮箱信息
r($mail->autoDetectTest($data[3])) && p('host') && e('smtp.vip.sohu.com');  //检测搜狐vip邮箱信息
r($mail->autoDetectTest($data[4])) && p()       && e('没有检测到相关信息'); //检测不存在的邮箱服务商信息

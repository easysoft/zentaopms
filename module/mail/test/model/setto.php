#!/usr/bin/env php
<?php

/**

title=测试 mailModel->setTO();
timeout=0
cid=0

- 不传入参数，检查admin账号的sended字段 @0
- 传入toList参数，检查admin账号的sended字段 @0
- 传入toList和mails参数，检查admin账号的sended字段属性sended @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$mails['admin'] = new stdclass();
$mails['admin']->email    = 'admin@cnezsoft.com';
$mails['admin']->realname = '管理员';

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->setTO(array(), array());
r(isset($mails['admin']->sended)) && p() && e('0'); //不传入参数，检查admin账号的sended字段

$mailModel->setTO(array('admin'), array());
r(isset($mails['admin']->sended)) && p() && e('0'); //传入toList参数，检查admin账号的sended字段

$mailModel->setTO(array('admin'), $mails);
r($mails['admin']) && p('sended') && e('1');        //传入toList和mails参数，检查admin账号的sended字段
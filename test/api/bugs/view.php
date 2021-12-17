#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 获取bug详细信息;
cid=1
pid=1

使用正确的bugID获取bug信息 >> BUG1
使用错误的bugID获取bug信息 >> 404 Not found

*/

global $token;
$existBug = $rest->get('/bugs/1', array('token' => $token->token));
$existBug->body = array($existBug->body);

$notExistBug = $rest->get('/bugs/200', array('token' => $token->token));

r($existBug)    && c(200) && p('title') && e('BUG1'); // 使用正确的bugID获取bug信息
r($notExistBug) && c(404) && p('error') && e('404 Not found'); // 使用错误的bugID获取bug信息
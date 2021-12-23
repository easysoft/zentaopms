#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=获取项目列表;
cid=1
pid=1

获取条目的project和type字段 >> 0,project
获取不存在的项目 >> 404 Not found
获取错误ID的项目 >> 404 Not found

*/
$token = $rest->post('/tokens', array('account' => 'admin', 'password' => '123456'));
$project = $rest->get('/projects/712', array("Token" => $token->body->token));
$zeroIdError = $rest->get('/projects/0', array("Token" => $token->body->token));
$stringIdError = $rest->get('/projects/test', array("Token" => $token->body->token));

r($project) && c('200') && p('project,type', ',') && e('0,project'); // 获取条目的project和type字段
r($zeroIdError) && c('404') && p('error') && e('404 Not found'); // 获取不存在的项目
r($stringIdError) && c('404') && p('error') && e('404 Not found'); // 获取错误ID的项目
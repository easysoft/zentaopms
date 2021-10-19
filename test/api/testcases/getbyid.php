#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 获取测试用例详情;
cid=1
pid=1

使用正确用例id查询用例详情 >> 这个是测试用例100
使用不存在的用例id查询用例详情 >> 404 Not found

*/
$token = $rest->post('/tokens', array('account' => 'admin', 'password' => '123456'));
$token = array('Token' => $token->body->token);

$existCase = $rest->get('/testcases/100', $token);
$noCase    = $rest->get('/testcases/100001', $token);

$existCase->body = array($existCase->body);
$noCase->body    = array($noCase->body);

r($existCase) && c(200) && p('title') && e('这个是测试用例100'); // 使用正确用例id查询用例详情
r($noCase)    && c(404) && p('error') && e('404 Not found');     // 使用不存在的用例id查询用例详情

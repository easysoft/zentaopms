#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 删除测试用例;
cid=1
pid=1

使用正确用例id删除用例 >> {"message":"success"}
使用不存在的用例id删除用例 >> {"message":"success"}
查询已删除的用例信息 >> 这个是测试用例1,1

*/
$token = $rest->post('/tokens', array('account' => 'admin', 'password' => '123456'));
$token = array('Token' => $token->body->token);

$deleteExist    = $rest->delete('/testcases/1', $token);
$deleteNotExist = $rest->delete('/testcases/10001', $token);

r($deleteExist)    && c(200) && p() && e('{"message":"success"}'); // 使用正确用例id删除用例
r($deleteNotExist) && c(200) && p() && e('{"message":"success"}'); // 使用不存在的用例id删除用例

$existCase = $rest->get('/testcases/1', $token);
$existCase->body = array($existCase->body);

r($existCase) && c(200) && p('title,deleted') && e('这个是测试用例1,1'); // 查询已删除的用例信息

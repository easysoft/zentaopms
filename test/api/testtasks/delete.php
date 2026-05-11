#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试API 删除测试单;
cid=1
pid=1

使用正确测试单id删除测试单 >> {"message":"success"}
查询已删除的测试单信息 >> 1,1

*/
global $token;
$header = array('Token' => $token->token);

$deleteExist = $rest->delete('/testtasks/1', $header);

r($deleteExist) && c(200) && p() && e('{"message":"success"}'); // 使用正确测试单id删除测试单

$existTesttask = $rest->get('/testtasks/1', $header);
$existTesttask->body = array($existTesttask->body);

r($existTesttask) && c(200) && p('id,deleted') && e('1,1'); // 查询已删除的测试单信息

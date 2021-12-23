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
global $token;
$header = array('Token' => $token->token);

$deleteExist    = $rest->delete('/testcases/1', $header);
$deleteNotExist = $rest->delete('/testcases/10001', $header);

r($deleteExist)    && c(200) && p() && e('{"message":"success"}'); // 使用正确用例id删除用例
r($deleteNotExist) && c(200) && p() && e('{"message":"success"}'); // 使用不存在的用例id删除用例

$existCase = $rest->get('/testcases/1', $header);
$existCase->body = array($existCase->body);

r($existCase) && c(200) && p('title,deleted') && e('这个是测试用例1,1'); // 查询已删除的用例信息
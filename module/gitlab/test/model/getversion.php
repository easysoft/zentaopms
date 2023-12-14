#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::getVersion();
timeout=0
cid=1

- 通过host,token检验api权限 @success
- 通过正确的host，错误的token获取api权限 @return false
- 通过错误或低版本的的host，token获取api权限 @return false

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID     = 1;
$gitlabServer = $tester->loadModel('pipeline')->getByID($gitlabID);

$result = $gitlab->getVersion($gitlabServer->url, $gitlabServer->token);
if(isset($result->version)) $result = 'success';
r($result) && p() && e('success'); //通过host,token检验api权限

$result = $gitlab->getVersion($gitlabServer->url, $gitlabServer->token . 'a');
if(!isset($result->version)) $result = 'return false';
r($result) && p() && e('return false'); //通过正确的host，错误的token获取api权限

$result = $gitlab->getVersion($gitlabServer->url . '1', $gitlabServer->token);
if(!isset($result->version)) $result = 'return false';
r($result) && p() && e('return false'); //通过错误或低版本的的host，token获取api权限
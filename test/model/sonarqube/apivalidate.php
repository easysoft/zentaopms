#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::apiValidate();
cid=1
pid=1

通过host,token检验api权限                      >> success
通过正确的host，错误的token获取api权限         >> return false
通过正确的host，非管理员权限的token获取api权限 >> return false

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID     = 2;
$sonarqubeServer = $tester->loadModel('pipeline')->getByID($sonarqubeID);
$result = $sonarqube->apiValidate($sonarqubeServer->url, $sonarqubeServer->token);
if(empty($result)) $result = 'success';
r($result) && p() && e('success'); //通过host,token检验api权限

$result = $sonarqube->apiValidate($sonarqubeServer->url, $sonarqubeServer->token . 'a');
if(isset($result['password'])) $result = 'return false';
r($result) && p() && e('return false'); //通过正确的host，错误的token获取api权限

$result = $sonarqube->apiValidate($sonarqubeServer->url, 'dGVzdDoxMjM0NTY=');
if(isset($result['account'])) $result = 'return false';
r($result) && p() && e('return false'); //通过正确的host，非管理员权限的token获取api权限

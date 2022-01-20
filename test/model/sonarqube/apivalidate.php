#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::apiValidate();
cid=1
pid=1

通过host,token检验api权限              >> 1
通过正确的host，错误的token获取api权限 >> return false

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID     = 2;
$sonarqubeServer = $sonarqube->getByID($sonarqubeID);
$result = $sonarqube->apiValidate($sonarqubeServer->url, $sonarqubeServer->token);
r($result) && p('valid') && e(1); //通过host,token检验api权限

$result = $sonarqube->apiValidate($sonarqubeServer->url, $sonarqubeServer->token . 'a');
if($result->valid == false) $result = 'return false';
r($result) && p('') && e(''); //通过正确的host，错误的token获取api权限

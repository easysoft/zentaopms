#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiValidate();
cid=0

- 通过host,token检验api权限 @success
- 通过正确的host，错误的token获取api权限 @return false
- 通过正确的host，非管理员权限的token获取api权限 @return false

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sonarqube.class.php';
su('admin');

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqubeID = 2;

$errorToken   = 'abc';
$noAdminToken = 'dGVzdDoxMjM0NTY=';

$sonarqube = new sonarqubeTest();
r($sonarqube->apiValidateTest($sonarqubeID))                     && p() && e('success');      //通过host,token检验api权限
r($sonarqube->apiValidateTest($sonarqubeID, '',  $errorToken))   && p() && e('return false'); //通过正确的host，错误的token获取api权限
r($sonarqube->apiValidateTest($sonarqubeID, '',  $noAdminToken)) && p() && e('return false'); //通过正确的host，非管理员权限的token获取api权限

#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiErrorHandling();
cid=0

- 使用空参数。第name条的0属性 @error
- 传入有错误信息的参数。
 - 第name条的0属性 @错误信息1
 - 第name条的1属性 @错误信息2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$sonarqube = $tester->loadModel('sonarqube');

$response = new stdclass();
$response->errors    = array();
$response->errors[0] = new stdclass();
$response->errors[0]->msg = '错误信息1';
$response->errors[1] = new stdclass();
$response->errors[1]->msg = '错误信息2';

$sonarqube->apiErrorHandling(null);
r(dao::getError()) && p('name:0') && e('error');    //使用空参数。

$sonarqube->apiErrorHandling($response);
r(dao::getError()) && p('name:0,1') && e('错误信息1,错误信息2'); //传入有错误信息的参数。

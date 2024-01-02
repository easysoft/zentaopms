#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::convertApiError();
cid=0

- 使用空参数。 @0
- 错误信息可以匹配传入的参数。 @权限不足
- 可以正则匹配错误信息。 @无法创建项目，项目标识已存在：新项目

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$sonarqube = $tester->loadModel('sonarqube');
$sonarqube->lang->sonarqube->apiErrorMap[3] = 'Could not create Project.';

r($sonarqube->convertApiError(''))                          && p() && e('0');        //使用空参数。
r($sonarqube->convertApiError('Could not create Project.')) && p() && e('权限不足'); //错误信息可以匹配传入的参数。
r($sonarqube->convertApiError('Could not create Project, key already exists: 新项目 ')) && p() && e('无法创建项目，项目标识已存在：新项目'); //可以正则匹配错误信息。

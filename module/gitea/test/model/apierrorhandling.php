#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiErrorHandling();
timeout=0
cid=0

- 对象里没有任何属性 @error
- 对象里有error属性 @some error
- 对象里有message属性属性name @名称已存在。
- 对象里有不能解析的message属性 @some message
- 对象里message属性是数组,且不能解析 @some message
- 对象里message属性是数组
 -  @some message
 - 属性name @名称已存在。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitea.class.php';

$giteaModel = new giteaTest();

$response = new stdclass();
r($giteaModel->apiErrorHandlingTester($response)) && p('0') && e('error'); // 对象里没有任何属性

$response->error = 'some error';
r($giteaModel->apiErrorHandlingTester($response)) && p('0') && e('some error'); // 对象里有error属性

unset($response->error);
$response->message = 'The repository with the same name already exists.';
r($giteaModel->apiErrorHandlingTester($response)) && p('name') && e('名称已存在。'); // 对象里有message属性

$response->message = 'some message';
r($giteaModel->apiErrorHandlingTester($response)) && p('0') && e('some message'); // 对象里有不能解析的message属性

$response->message = array('some message');
r($giteaModel->apiErrorHandlingTester($response)) && p('0') && e('some message'); // 对象里message属性是数组,且不能解析

$response->message[] = 'The repository with the same name already exists.';
r($giteaModel->apiErrorHandlingTester($response)) && p('0,name') && e('some message,名称已存在。'); // 对象里message属性是数组
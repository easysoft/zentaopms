#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::parseApiError();
timeout=0
cid=0

- 不能解析的错误信息 @some error
- 正常解析的错误信息属性name @名称已存在。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitea.class.php';

$giteaModel = new giteaTest();

$message = 'some error';
r($giteaModel->parseApiErrorTester($message)) && p('0') && e('some error'); // 不能解析的错误信息

$message = 'The repository with the same name already exists.';
r($giteaModel->parseApiErrorTester($message)) && p('name') && e('名称已存在。'); // 正常解析的错误信息
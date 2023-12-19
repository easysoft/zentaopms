#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->isError();
cid=1

- 测试 static::$errors 为 errors 时，是否有报错 @1
- 测试 static::$errors 为 emptyErrors 时，是否有报错 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$upgrade = new upgradeTest();

$errors      = array('error1', 'error2', 'error3');
$emptyErrors = array();
r($upgrade->isErrorTest($errors))      && p() && e('1'); // 测试 static::$errors 为 errors 时，是否有报错
r($upgrade->isErrorTest($emptyErrors)) && p() && e('0'); // 测试 static::$errors 为 emptyErrors 时，是否有报错

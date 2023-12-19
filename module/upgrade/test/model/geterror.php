#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getError();
cid=1

- 测试 static::$errors 为 errors1 时的错误内容 @error1,error2,error3

- 测试 static::$errors 为 errors2 时的错误内容 @error4,error5,error6

- 测试 static::$errors 为 emptyErrors 时的错误内容 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->config('user')->gen(5);

su('admin');

$upgrade = new upgradeTest();

$errors1     = array('error1', 'error2', 'error3');
$errors2     = array('error4', 'error5', 'error6');
$emptyErrors = array();
r($upgrade->getErrorTest($errors1))     && p() && e('error1,error2,error3'); // 测试 static::$errors 为 errors1 时的错误内容
r($upgrade->getErrorTest($errors2))     && p() && e('error4,error5,error6'); // 测试 static::$errors 为 errors2 时的错误内容
r($upgrade->getErrorTest($emptyErrors)) && p() && e('0');                    // 测试 static::$errors 为 emptyErrors 时的错误内容

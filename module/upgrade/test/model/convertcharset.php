#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('doc')->gen(5);
zenData('docaction')->gen(0);

/**

title=测试 upgradeModel->convertCharset();
cid=1

- 检查转换字符集 @1

**/

global $tester;
$upgradeModel = $tester->loadModel('upgrade');
r($upgradeModel->convertCharset()) && p() && e('1');  // 检查转换字符集

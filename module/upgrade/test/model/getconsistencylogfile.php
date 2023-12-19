#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getConsistencyLogFile();
cid=1

- 测试获取一致性检查的日志文件地址是否正确 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

global $app;
$path     = $app->getTmpRoot() . 'log/';
$filename = 'consistency.%s.log.php';

$filePath = sprintf($path . $filename, date('Ymd'));

$upgrade = new upgradeTest();
r($upgrade->getConsistencyLogFile() === $filePath) && p('') && e('1');  //获取一致性检查的日志文件地址是否正确

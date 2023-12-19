#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->stopOldCron();
cid=1

- 删除文件后，调用stopOldCron()，文件若重新生成，返回true   @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

global $app;
$path = $app->getCacheRoot() . 'restartcron';

if(!file_exists($app->getCacheRoot())) mkdir($app->getCacheRoot());

if(file_exists($path)) unlink($path);

$upgrade->stopOldCron();
r(file_exists($path)) && p('') && e(1);  //删除文件后，调用stopOldCron()，文件若重新生成，返回true

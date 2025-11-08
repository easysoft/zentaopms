#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::backupDB();
timeout=0
cid=0

- 执行extensionTest模块的backupDBTest方法，参数是$testExt1  @testplugin1.20251109.sql
- 执行extensionTest模块的backupDBTest方法，参数是$testExt2  @0
- 执行extensionTest模块的backupDBTest方法，参数是$testExt3  @0
- 执行extensionTest模块的backupDBTest方法，参数是$testExt4  @testplugin4.20251109.sql
- 执行extensionTest模块的backupDBTest方法，参数是$testExt5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

/* 准备测试环境 */
$extensionRoot = $app->getExtensionRoot();
$pkgRoot = $extensionRoot . 'pkg' . DS;

/* 创建测试插件目录 */
if(!is_dir($pkgRoot)) mkdir($pkgRoot, 0777, true);

/* 测试用例1: 正常插件的备份,使用真实存在的表user */
$testExt1 = 'testplugin1';
$testExt1Dir = $pkgRoot . $testExt1;
if(!is_dir($testExt1Dir)) mkdir($testExt1Dir, 0777, true);
if(!is_dir($testExt1Dir . DS . 'db')) mkdir($testExt1Dir . DS . 'db', 0777, true);
file_put_contents($testExt1Dir . DS . 'db' . DS . 'uninstall.sql', "DROP TABLE IF EXISTS `zt_user`;");

/* 测试用例2: uninstall.sql只包含注释 */
$testExt2 = 'testplugin2';
$testExt2Dir = $pkgRoot . $testExt2;
if(!is_dir($testExt2Dir)) mkdir($testExt2Dir, 0777, true);
if(!is_dir($testExt2Dir . DS . 'db')) mkdir($testExt2Dir . DS . 'db', 0777, true);
file_put_contents($testExt2Dir . DS . 'db' . DS . 'uninstall.sql', "-- Only comments\n-- No drop or delete");

/* 测试用例3: 空的uninstall.sql */
$testExt3 = 'testplugin3';
$testExt3Dir = $pkgRoot . $testExt3;
if(!is_dir($testExt3Dir)) mkdir($testExt3Dir, 0777, true);
if(!is_dir($testExt3Dir . DS . 'db')) mkdir($testExt3Dir . DS . 'db', 0777, true);
file_put_contents($testExt3Dir . DS . 'db' . DS . 'uninstall.sql', "");

/* 测试用例4: 包含多个真实表 */
$testExt4 = 'testplugin4';
$testExt4Dir = $pkgRoot . $testExt4;
if(!is_dir($testExt4Dir)) mkdir($testExt4Dir, 0777, true);
if(!is_dir($testExt4Dir . DS . 'db')) mkdir($testExt4Dir . DS . 'db', 0777, true);
file_put_contents($testExt4Dir . DS . 'db' . DS . 'uninstall.sql', "DROP TABLE IF EXISTS `zt_user`;\nDROP TABLE IF EXISTS `zt_config`;");

/* 测试用例5: 没有DROP TABLE语句 */
$testExt5 = 'testplugin5';
$testExt5Dir = $pkgRoot . $testExt5;
if(!is_dir($testExt5Dir)) mkdir($testExt5Dir, 0777, true);
if(!is_dir($testExt5Dir . DS . 'db')) mkdir($testExt5Dir . DS . 'db', 0777, true);
file_put_contents($testExt5Dir . DS . 'db' . DS . 'uninstall.sql', "-- Comment only");

$extensionTest = new extensionZenTest();

r($extensionTest->backupDBTest($testExt1)) && p() && e('testplugin1.20251109.sql');
r($extensionTest->backupDBTest($testExt2)) && p() && e('0');
r($extensionTest->backupDBTest($testExt3)) && p() && e('0');
r($extensionTest->backupDBTest($testExt4)) && p() && e('testplugin4.20251109.sql');
r($extensionTest->backupDBTest($testExt5)) && p() && e('0');
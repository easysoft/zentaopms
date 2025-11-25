#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::copyPackageFiles();
timeout=0
cid=16485

- 执行$result1 @1
- 执行$result2 @0
- 执行$result3 @0
- 执行$result4) > 0 @1
- 执行$md5Len @32

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

$extensionTest = new extensionZenTest();

// 获取测试路径
$extensionModel = $tester->loadModel('extension');
$pkgRoot = $extensionModel->pkgRoot;

// 步骤1: 测试空插件包目录
$ext1 = 'test_empty_pkg';
$dir1 = $pkgRoot . $ext1 . DS;
if(!is_dir($dir1)) mkdir($dir1, 0777, true);
$result1 = $extensionTest->copyPackageFilesTest($ext1);
r(is_array($result1)) && p() && e('1');
if(is_dir($dir1)) rmdir($dir1);

// 步骤2: 测试只有db目录的插件包
$ext2 = 'test_db_pkg';
$dir2 = $pkgRoot . $ext2 . DS;
$dbDir2 = $dir2 . 'db' . DS;
if(!is_dir($dbDir2)) mkdir($dbDir2, 0777, true);
file_put_contents($dbDir2 . 'test.sql', 'SQL');
$result2 = $extensionTest->copyPackageFilesTest($ext2);
r(count($result2)) && p() && e('0');
if(file_exists($dbDir2 . 'test.sql')) unlink($dbDir2 . 'test.sql');
if(is_dir($dbDir2)) rmdir($dbDir2);
if(is_dir($dir2)) rmdir($dir2);

// 步骤3: 测试只有doc目录的插件包
$ext3 = 'test_doc_pkg';
$dir3 = $pkgRoot . $ext3 . DS;
$docDir3 = $dir3 . 'doc' . DS;
if(!is_dir($docDir3)) mkdir($docDir3, 0777, true);
file_put_contents($docDir3 . 'readme.md', 'README');
$result3 = $extensionTest->copyPackageFilesTest($ext3);
r(count($result3)) && p() && e('0');
if(file_exists($docDir3 . 'readme.md')) unlink($docDir3 . 'readme.md');
if(is_dir($docDir3)) rmdir($docDir3);
if(is_dir($dir3)) rmdir($dir3);

// 步骤4: 测试包含module文件的插件包
$ext4 = 'test_module_pkg';
$dir4 = $pkgRoot . $ext4 . DS;
$modDir4 = $dir4 . 'module' . DS . 'testcopy' . DS;
if(!is_dir($modDir4)) mkdir($modDir4, 0777, true);
file_put_contents($modDir4 . 'test.php', '<?php');
$result4 = $extensionTest->copyPackageFilesTest($ext4);
r(count($result4) > 0) && p() && e('1');
$targetDir4 = $app->getAppRoot() . 'module' . DS . 'testcopy' . DS;
if(file_exists($targetDir4 . 'test.php')) unlink($targetDir4 . 'test.php');
if(is_dir($targetDir4)) rmdir($targetDir4);
if(file_exists($modDir4 . 'test.php')) unlink($modDir4 . 'test.php');
if(is_dir($modDir4)) rmdir($modDir4);
if(is_dir($dir4 . 'module')) rmdir($dir4 . 'module');
if(is_dir($dir4)) rmdir($dir4);

// 步骤5: 测试返回值MD5格式
$ext5 = 'test_md5_pkg';
$dir5 = $pkgRoot . $ext5 . DS;
$cfgDir5 = $dir5 . 'config' . DS;
if(!is_dir($cfgDir5)) mkdir($cfgDir5, 0777, true);
file_put_contents($cfgDir5 . 'test.php', '<?php');
$result5 = $extensionTest->copyPackageFilesTest($ext5);
$md5Len = 0;
foreach($result5 as $file => $md5) { $md5Len = strlen($md5); break; }
r($md5Len) && p() && e('32');
$targetCfg5 = $app->getAppRoot() . 'config' . DS;
foreach($result5 as $file => $md5) if(file_exists($file)) unlink($file);
if(file_exists($cfgDir5 . 'test.php')) unlink($cfgDir5 . 'test.php');
if(is_dir($cfgDir5)) rmdir($cfgDir5);
if(is_dir($dir5)) rmdir($dir5);
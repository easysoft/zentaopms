#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::getHookFile();
timeout=0
cid=16488

- 执行$result1 !== false && strpos($result1, 'preinstall.php') !== false @1
- 执行$result2 !== false && strpos($result2, 'postinstall.php') !== false @1
- 执行$result3 @0
- 执行$result4 @0
- 执行$result5 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

$extensionTest = new extensionZenTest();

// 获取测试路径
$extensionModel = $tester->loadModel('extension');
$pkgRoot = $extensionModel->pkgRoot;

// 步骤1: 测试preinstall钩子文件存在的情况
$ext1 = 'test_hook_preinstall';
$hookDir1 = $pkgRoot . $ext1 . DS . 'hook' . DS;
if(!is_dir($hookDir1)) mkdir($hookDir1, 0777, true);
$preinstallFile = $hookDir1 . 'preinstall.php';
file_put_contents($preinstallFile, '<?php // preinstall hook');
$result1 = $extensionTest->getHookFileTest($ext1, 'preinstall');
r($result1 !== false && strpos($result1, 'preinstall.php') !== false) && p() && e('1');
if(file_exists($preinstallFile)) unlink($preinstallFile);
if(is_dir($hookDir1)) rmdir($hookDir1);
if(is_dir($pkgRoot . $ext1)) rmdir($pkgRoot . $ext1);

// 步骤2: 测试postinstall钩子文件存在的情况
$ext2 = 'test_hook_postinstall';
$hookDir2 = $pkgRoot . $ext2 . DS . 'hook' . DS;
if(!is_dir($hookDir2)) mkdir($hookDir2, 0777, true);
$postinstallFile = $hookDir2 . 'postinstall.php';
file_put_contents($postinstallFile, '<?php // postinstall hook');
$result2 = $extensionTest->getHookFileTest($ext2, 'postinstall');
r($result2 !== false && strpos($result2, 'postinstall.php') !== false) && p() && e('1');
if(file_exists($postinstallFile)) unlink($postinstallFile);
if(is_dir($hookDir2)) rmdir($hookDir2);
if(is_dir($pkgRoot . $ext2)) rmdir($pkgRoot . $ext2);

// 步骤3: 测试钩子文件不存在的情况
$ext3 = 'test_hook_notexist';
$hookDir3 = $pkgRoot . $ext3 . DS . 'hook' . DS;
if(!is_dir($hookDir3)) mkdir($hookDir3, 0777, true);
$result3 = $extensionTest->getHookFileTest($ext3, 'preuninstall');
r($result3) && p() && e('0');
if(is_dir($hookDir3)) rmdir($hookDir3);
if(is_dir($pkgRoot . $ext3)) rmdir($pkgRoot . $ext3);

// 步骤4: 测试插件目录不存在的情况
$ext4 = 'test_hook_nodir';
$result4 = $extensionTest->getHookFileTest($ext4, 'postuninstall');
r($result4) && p() && e('0');

// 步骤5: 测试hook目录不存在但插件目录存在的情况
$ext5 = 'test_hook_nohookdir';
$extDir5 = $pkgRoot . $ext5 . DS;
if(!is_dir($extDir5)) mkdir($extDir5, 0777, true);
$result5 = $extensionTest->getHookFileTest($ext5, 'preinstall');
r($result5) && p() && e('0');
if(is_dir($extDir5)) rmdir($extDir5);
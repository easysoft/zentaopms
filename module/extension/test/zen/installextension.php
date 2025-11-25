#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::installExtension();
timeout=0
cid=16489

- 执行$result1 @1
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

$extensionTest = new extensionZenTest();

// 获取扩展模型
$extensionModel = $tester->loadModel('extension');
$pkgRoot = $extensionModel->pkgRoot;

// 准备测试数据
zenData('extension')->gen(0);

// 步骤1: 正常安装新插件(upgrade=no,无install.sql)
$ext1 = 'test_install_basic';
$extDir1 = $pkgRoot . $ext1 . DS;
if(!is_dir($extDir1)) mkdir($extDir1, 0777, true);
if(!is_dir($extDir1 . 'module')) mkdir($extDir1 . 'module', 0777, true);
file_put_contents($extDir1 . 'module' . DS . 'test.php', '<?php // test module');
$result1 = $extensionTest->installExtensionTest($ext1, 'extension', 'no');
r($result1) && p() && e('1');
if(is_dir($extDir1)) $extensionModel->classFile->removeDir($extDir1);

// 步骤2: 升级已有插件(upgrade=yes)
$ext2 = 'test_install_upgrade';
$extDir2 = $pkgRoot . $ext2 . DS;
if(!is_dir($extDir2)) mkdir($extDir2, 0777, true);
if(!is_dir($extDir2 . 'module')) mkdir($extDir2 . 'module', 0777, true);
file_put_contents($extDir2 . 'module' . DS . 'test.php', '<?php // test module');
$hookDir2 = $extDir2 . 'hook' . DS;
if(!is_dir($hookDir2)) mkdir($hookDir2, 0777, true);
file_put_contents($hookDir2 . 'preupgrade.php', '<?php // preupgrade hook');
file_put_contents($hookDir2 . 'postupgrade.php', '<?php // postupgrade hook');
$result2 = $extensionTest->installExtensionTest($ext2, 'extension', 'yes');
r($result2) && p() && e('1');
if(is_dir($extDir2)) $extensionModel->classFile->removeDir($extDir2);

// 步骤3: 正常安装新插件(upgrade=no,有install.sql且执行成功)
$ext3 = 'test_install_withdb';
$extDir3 = $pkgRoot . $ext3 . DS;
if(!is_dir($extDir3)) mkdir($extDir3, 0777, true);
if(!is_dir($extDir3 . 'module')) mkdir($extDir3 . 'module', 0777, true);
file_put_contents($extDir3 . 'module' . DS . 'test.php', '<?php // test module');
$dbDir3 = $extDir3 . 'db' . DS;
if(!is_dir($dbDir3)) mkdir($dbDir3, 0777, true);
file_put_contents($dbDir3 . 'install.sql', '-- test install sql');
$result3 = $extensionTest->installExtensionTest($ext3, 'extension', 'no');
r($result3) && p() && e('1');
if(is_dir($extDir3)) $extensionModel->classFile->removeDir($extDir3);

// 步骤4: 测试插件包文件复制功能
$ext4 = 'test_install_copyfiles';
$extDir4 = $pkgRoot . $ext4 . DS;
if(!is_dir($extDir4)) mkdir($extDir4, 0777, true);
if(!is_dir($extDir4 . 'module')) mkdir($extDir4 . 'module', 0777, true);
file_put_contents($extDir4 . 'module' . DS . 'test.php', '<?php // test module');
$hookDir4 = $extDir4 . 'hook' . DS;
if(!is_dir($hookDir4)) mkdir($hookDir4, 0777, true);
file_put_contents($hookDir4 . 'preinstall.php', '<?php // preinstall hook');
file_put_contents($hookDir4 . 'postinstall.php', '<?php // postinstall hook');
$result4 = $extensionTest->installExtensionTest($ext4, 'extension', 'no');
r($result4) && p() && e('1');
if(is_dir($extDir4)) $extensionModel->classFile->removeDir($extDir4);

// 步骤5: 测试插件状态更新为installed
$ext5 = 'test_install_status';
$extDir5 = $pkgRoot . $ext5 . DS;
if(!is_dir($extDir5)) mkdir($extDir5, 0777, true);
if(!is_dir($extDir5 . 'module')) mkdir($extDir5 . 'module', 0777, true);
file_put_contents($extDir5 . 'module' . DS . 'test.php', '<?php // test module');
$result5 = $extensionTest->installExtensionTest($ext5, 'extension', 'no');
r($result5) && p() && e('1');
if(is_dir($extDir5)) $extensionModel->classFile->removeDir($extDir5);
#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkFileConflict();
timeout=0
cid=16481

- 执行func模块的invokeArgs方法，参数是$zenInstance, array 属性result @ok
- 执行func模块的invokeArgs方法，参数是$zenInstance, array 属性result @ok
- 执行func模块的invokeArgs方法，参数是$zenInstance, array 属性result @ok
- 执行func模块的invokeArgs方法，参数是$zenInstance, array 属性result @fail
- 执行func模块的invokeArgs方法，参数是$zenInstance, array 属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

// 初始化extension模型和zen实例
$extensionModel = $tester->loadModel('extension');
$zen = initReference('extension');
$func = $zen->getMethod('checkFileConflict');

// 创建zen实例并设置extension属性
$zenInstance = $zen->newInstance();
$zenInstance->extension = $extensionModel;

// 创建测试环境
$testPkgRoot = $extensionModel->pkgRoot;
$appRoot = $app->getAppRoot();

// 测试步骤1：正常插件目录无冲突文件
$normalExt = 'normalext';
$normalExtDir = $testPkgRoot . $normalExt;
if(!is_dir($normalExtDir)) mkdir($normalExtDir, 0777, true);
file_put_contents($normalExtDir . '/uniquefile.php', '<?php // unique content');
r($func->invokeArgs($zenInstance, array($normalExt))) && p('result') && e('ok');

// 测试步骤2：空插件名称参数
r($func->invokeArgs($zenInstance, array(''))) && p('result') && e('ok');

// 测试步骤3：不存在的插件目录
r($func->invokeArgs($zenInstance, array('nonexistentextension'))) && p('result') && e('ok');

// 测试步骤4：存在冲突文件的插件
$conflictExt = 'conflictext';
$conflictExtDir = $testPkgRoot . $conflictExt;
if(!is_dir($conflictExtDir)) mkdir($conflictExtDir, 0777, true);
// 创建一个与现有文件冲突的文件
$existingFile = $appRoot . 'www/index.php';
$conflictFile = $conflictExtDir . '/www/index.php';
if(!is_dir(dirname($conflictFile))) mkdir(dirname($conflictFile), 0777, true);
file_put_contents($conflictFile, '<?php // different content');
r($func->invokeArgs($zenInstance, array($conflictExt))) && p('result') && e('fail');

// 测试步骤5：多个冲突文件的插件  
$multiConflictExt = 'multiconflictext';
$multiConflictExtDir = $testPkgRoot . $multiConflictExt;
if(!is_dir($multiConflictExtDir)) mkdir($multiConflictExtDir, 0777, true);
// 创建多个冲突文件
$conflictFile1 = $multiConflictExtDir . '/www/index.php';
$conflictFile2 = $multiConflictExtDir . '/config/config.php';
if(!is_dir(dirname($conflictFile1))) mkdir(dirname($conflictFile1), 0777, true);
if(!is_dir(dirname($conflictFile2))) mkdir(dirname($conflictFile2), 0777, true);
file_put_contents($conflictFile1, '<?php // different content 1');
file_put_contents($conflictFile2, '<?php // different content 2');
r($func->invokeArgs($zenInstance, array($multiConflictExt))) && p('result') && e('fail');

// 清理测试环境
if(is_dir($normalExtDir)) {
    if(file_exists($normalExtDir . '/uniquefile.php')) unlink($normalExtDir . '/uniquefile.php');
    rmdir($normalExtDir);
}
if(is_dir($conflictExtDir)) {
    if(file_exists($conflictFile)) unlink($conflictFile);
    if(is_dir(dirname($conflictFile))) rmdir(dirname($conflictFile));
    rmdir($conflictExtDir);
}
if(is_dir($multiConflictExtDir)) {
    if(file_exists($conflictFile1)) unlink($conflictFile1);
    if(file_exists($conflictFile2)) unlink($conflictFile2);
    if(is_dir(dirname($conflictFile1))) rmdir(dirname($conflictFile1));
    if(is_dir(dirname($conflictFile2))) rmdir(dirname($conflictFile2));
    rmdir($multiConflictExtDir);
}
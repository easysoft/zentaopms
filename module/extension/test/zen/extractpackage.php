#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::extractPackage();
timeout=0
cid=16486

- 执行$result1->result @ok
- 执行$extractedDir1 @1
- 执行$extractedFile1 @1
- 执行$oldFile) && $result4->result == 'ok @1
- 执行$result5->result @ok

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

$extensionTest = new extensionZenTest();
$extensionModel = $tester->loadModel('extension');
$pkgRoot = $extensionModel->pkgRoot;
$tmpRoot = $app->getTmpRoot() . 'extension' . DS;

// 确保目录存在
if(!is_dir($tmpRoot)) mkdir($tmpRoot, 0777, true);
if(!is_dir($pkgRoot)) mkdir($pkgRoot, 0777, true);

// 创建测试用的插件包
function createTestPackage($tmpRoot, $extName, $withFiles = true)
{
    global $app;

    // 创建临时目录结构
    $tempDir = sys_get_temp_dir() . DS . 'test_ext_' . uniqid();
    $extDir = $tempDir . DS . $extName;
    if(!is_dir($extDir)) mkdir($extDir, 0777, true);

    if($withFiles)
    {
        // 创建测试文件
        $moduleDir = $extDir . DS . 'module' . DS . 'test';
        if(!is_dir($moduleDir)) mkdir($moduleDir, 0777, true);
        file_put_contents($moduleDir . DS . 'test.php', '<?php // test');
    }

    // 创建zip包使用pclzip
    $zipFile = $tmpRoot . $extName . '.zip';
    $app->loadClass('pclzip', true);
    $zip = new pclzip($zipFile);

    // 添加整个目录到zip
    $zip->create($extDir, PCLZIP_OPT_REMOVE_PATH, dirname($extDir));

    // 清理临时目录
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($tempDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach($files as $file) {
        $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
    }
    rmdir($tempDir);

    return $zipFile;
}

// 清理目录函数
function removeDir($dir)
{
    if(!is_dir($dir)) return;
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach($files as $file)
    {
        $path = $dir . DS . $file;
        is_dir($path) ? removeDir($path) : unlink($path);
    }
    rmdir($dir);
}

// 步骤1: 测试成功解压插件包
$ext1 = 'test_extract_' . uniqid();
$zipFile1 = createTestPackage($tmpRoot, $ext1, true);
$result1 = $extensionTest->extractPackageTest($ext1);
r($result1->result) && p() && e('ok');

// 步骤2: 测试解压后的目录存在
$extractedDir1 = $pkgRoot . $ext1;
r(is_dir($extractedDir1)) && p() && e('1');

// 步骤3: 测试解压后的文件存在
$extractedFile1 = $extractedDir1 . DS . 'module' . DS . 'test' . DS . 'test.php';
r(file_exists($extractedFile1)) && p() && e('1');

// 步骤4: 测试重复解压会清理旧目录
$oldFile = $extractedDir1 . DS . 'old_file.txt';
file_put_contents($oldFile, 'old content');
$result4 = $extensionTest->extractPackageTest($ext1);
r(!file_exists($oldFile) && $result4->result == 'ok') && p() && e('1');

// 步骤5: 测试解压空插件包(只有目录结构)
$ext5 = 'test_empty_' . uniqid();
$zipFile5 = createTestPackage($tmpRoot, $ext5, false);
$result5 = $extensionTest->extractPackageTest($ext5);
r($result5->result) && p() && e('ok');

// 清理测试数据
if(file_exists($zipFile1)) unlink($zipFile1);
if(is_dir($extractedDir1)) removeDir($extractedDir1);
if(file_exists($zipFile5)) unlink($zipFile5);
$extractedDir5 = $pkgRoot . $ext5;
if(is_dir($extractedDir5)) removeDir($extractedDir5);
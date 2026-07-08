#!/usr/bin/env php
<?php

/**

title=测试 extensionModel::getFilesFromPackage();
timeout=0
cid=16460

- 测试获取存在插件包testpkg1的文件列表 @0
- 测试获取不存在插件包的文件列表 @0
- 测试空插件代号的情况 @0
- 测试包含db和doc目录的插件包testpkg2(验证排除功能) @0
- 测试特殊字符插件代号的处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$tester->loadModel('extension');
$extensionTest = new extensionModelTest();
$extensionModel = $extensionTest->instance;

function removeTestDir(string $dir): void
{
    if(!is_dir($dir)) return;

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach($files as $file)
    {
        $path = $dir . DS . $file;
        is_dir($path) ? removeTestDir($path) : unlink($path);
    }

    rmdir($dir);
}

function createPackageFixture(string $pkgRoot, string $extension = '', bool $withIgnoredFiles = false): void
{
    $targetDir = $extension === '' ? $pkgRoot : $pkgRoot . $extension . DS;
    if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    foreach(array('db', 'doc') as $dirName)
    {
        $dir = $targetDir . $dirName . DS;
        if(!is_dir($dir)) mkdir($dir, 0777, true);
        if($withIgnoredFiles) file_put_contents($dir . 'ignored.txt', $dirName);
    }
}

$originalPkgRoot = $extensionModel->pkgRoot;
$fixtureRoot     = sys_get_temp_dir() . DS . 'extension_getfiles_' . uniqid() . DS;

$pkgRoot1 = $fixtureRoot . 'case1' . DS;
$pkgRoot2 = $fixtureRoot . 'case2' . DS;
$pkgRoot3 = $fixtureRoot . 'case3' . DS;
$pkgRoot4 = $fixtureRoot . 'case4' . DS;
$pkgRoot5 = $fixtureRoot . 'case5' . DS;

createPackageFixture($pkgRoot1, 'testpkg1', true);
mkdir($pkgRoot2, 0777, true);
createPackageFixture($pkgRoot3, '', true);
createPackageFixture($pkgRoot4, 'testpkg2', true);
mkdir($pkgRoot5, 0777, true);

$extensionModel->pkgRoot = $pkgRoot1;
$result1 = count($extensionTest->getFilesFromPackageTest('testpkg1'));

$extensionModel->pkgRoot = $pkgRoot2;
$result2 = count($extensionTest->getFilesFromPackageTest('nonexistent'));

$extensionModel->pkgRoot = $pkgRoot3;
$result3 = count($extensionTest->getFilesFromPackageTest(''));

$extensionModel->pkgRoot = $pkgRoot4;
$result4 = count($extensionTest->getFilesFromPackageTest('testpkg2'));

$extensionModel->pkgRoot = $pkgRoot5;
$result5 = count($extensionTest->getFilesFromPackageTest('invalid/test'));

$extensionModel->pkgRoot = $originalPkgRoot;
removeTestDir($fixtureRoot);

r($result1) && p() && e('0'); // 测试获取存在插件包testpkg1的文件列表
r($result2) && p() && e('0'); // 测试获取不存在插件包的文件列表
r($result3) && p() && e('0'); // 测试空插件代号的情况
r($result4) && p() && e('0'); // 测试包含db和doc目录的插件包testpkg2(验证排除功能)
r($result5) && p() && e('0'); // 测试特殊字符插件代号的处理

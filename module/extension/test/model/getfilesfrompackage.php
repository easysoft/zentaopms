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

r(count($extensionTest->getFilesFromPackageTest('testpkg1')))     && p() && e('0'); // 测试获取存在插件包testpkg1的文件列表
r(count($extensionTest->getFilesFromPackageTest('nonexistent')))  && p() && e('0'); // 测试获取不存在插件包的文件列表
r(count($extensionTest->getFilesFromPackageTest('')))             && p() && e('0'); // 测试空插件代号的情况
r(count($extensionTest->getFilesFromPackageTest('testpkg2')))     && p() && e('0'); // 测试包含db和doc目录的插件包testpkg2(验证排除功能)
r(count($extensionTest->getFilesFromPackageTest('invalid/test'))) && p() && e('0'); // 测试特殊字符插件代号的处理

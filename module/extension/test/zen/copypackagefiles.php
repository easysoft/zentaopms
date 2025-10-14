#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::copyPackageFiles();
timeout=0
cid=0

- 执行extensionTest模块的copyPackageFilesTest方法，参数是'testextension'  @0
- 执行extensionTest模块的copyPackageFilesTest方法，参数是'nonexistentextension'  @0
- 执行extensionTest模块的copyPackageFilesTest方法，参数是''  @0
- 执行extensionTest模块的copyPackageFilesTest方法，参数是'invalidextension'  @0
- 执行extensionTest模块的copyPackageFilesTest方法，参数是'emptyextension'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

zenData('extension')->loadYaml('extension_copypackagefiles', false, 2)->gen(10);

su('admin');

$extensionTest = new extensionTest();

r($extensionTest->copyPackageFilesTest('testextension')) && p() && e('0');
r($extensionTest->copyPackageFilesTest('nonexistentextension')) && p() && e('0');
r($extensionTest->copyPackageFilesTest('')) && p() && e('0');
r($extensionTest->copyPackageFilesTest('invalidextension')) && p() && e('0');
r($extensionTest->copyPackageFilesTest('emptyextension')) && p() && e('0');
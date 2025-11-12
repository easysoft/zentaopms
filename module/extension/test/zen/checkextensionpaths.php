#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkExtensionPaths();
timeout=0
cid=0

- 执行extensionTest模块的checkExtensionPathsTest方法，参数是'nonexistent_plugin' 属性result @ok
- 执行extensionTest模块的checkExtensionPathsTest方法，参数是'' 属性result @ok
- 执行extensionTest模块的checkExtensionPathsTest方法，参数是'test-plugin-123' 属性result @ok
- 执行extensionTest模块的checkExtensionPathsTest方法，参数是'test_plugin' 属性result @ok
- 执行extensionTest模块的checkExtensionPathsTest方法，参数是'another_test' 属性result @ok

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

$extensionTest = new extensionZenTest();

r($extensionTest->checkExtensionPathsTest('nonexistent_plugin')) && p('result') && e('ok');
r($extensionTest->checkExtensionPathsTest('')) && p('result') && e('ok');
r($extensionTest->checkExtensionPathsTest('test-plugin-123')) && p('result') && e('ok');
r($extensionTest->checkExtensionPathsTest('test_plugin')) && p('result') && e('ok');
r($extensionTest->checkExtensionPathsTest('another_test')) && p('result') && e('ok');
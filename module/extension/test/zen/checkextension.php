#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkExtension();
timeout=0
cid=0

- 执行extensionTest模块的checkExtensionTest方法，参数是'testplugin1', 'no', '', 'no', '', 'install'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'testplugin2', 'yes', '', 'no', '', 'install'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'testplugin3', 'no', '', 'yes', '', 'upgrade'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'', 'no', '', 'no', '', 'install'  @0
- 执行extensionTest模块的checkExtensionTest方法，参数是'nonexistent', 'no', '', 'no', '', 'install'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

zendata('extension')->loadYaml('extension_checkextension', false, 2)->gen(5);

su('admin');

$extensionTest = new extensionTest();

r($extensionTest->checkExtensionTest('testplugin1', 'no', '', 'no', '', 'install')) && p() && e('0');
r($extensionTest->checkExtensionTest('testplugin2', 'yes', '', 'no', '', 'install')) && p() && e('0');
r($extensionTest->checkExtensionTest('testplugin3', 'no', '', 'yes', '', 'upgrade')) && p() && e('0');
r($extensionTest->checkExtensionTest('', 'no', '', 'no', '', 'install')) && p() && e('0');
r($extensionTest->checkExtensionTest('nonexistent', 'no', '', 'no', '', 'install')) && p() && e('0');
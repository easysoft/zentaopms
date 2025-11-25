#!/usr/bin/env php
<?php

/**

title=测试 storeModel::getUpgradableVersions();
timeout=0
cid=18455

- 执行storeTest模块的getUpgradableVersionsTest方法，参数是'1.0.0', 1  @0
- 执行storeTest模块的getUpgradableVersionsTest方法，参数是'1.0.0', 0, 'testapp'  @0
- 执行storeTest模块的getUpgradableVersionsTest方法，参数是'1.0.0', 1, '', 'stable'  @0
- 执行storeTest模块的getUpgradableVersionsTest方法，参数是'1.0.0', 999999  @0
- 执行storeTest模块的getUpgradableVersionsTest方法，参数是'1.0.0', 1, 'testapp'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/store.unittest.class.php';

su('admin');

$storeTest = new storeTest();

r($storeTest->getUpgradableVersionsTest('1.0.0', 1)) && p() && e('0');
r($storeTest->getUpgradableVersionsTest('1.0.0', 0, 'testapp')) && p() && e('0');
r($storeTest->getUpgradableVersionsTest('1.0.0', 1, '', 'stable')) && p() && e('0');
r($storeTest->getUpgradableVersionsTest('1.0.0', 999999)) && p() && e('0');
r($storeTest->getUpgradableVersionsTest('1.0.0', 1, 'testapp')) && p() && e('0');
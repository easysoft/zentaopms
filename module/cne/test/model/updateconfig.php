#!/usr/bin/env php
<?php

/**

title=测试 cneModel::updateConfig();
timeout=0
cid=15634

- 执行cneTest模块的updateConfigTest方法  @0
- 执行cneTest模块的updateConfigTest方法，参数是'2024.04.2401'  @0
- 执行cneTest模块的updateConfigTest方法，参数是null, true  @0
- 执行cneTest模块的updateConfigTest方法，参数是null, null, array  @0
- 执行cneTest模块的updateConfigTest方法，参数是null, false, null,   @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->updateConfigTest()) && p() && e('0');
r($cneTest->updateConfigTest('2024.04.2401')) && p() && e('0');
r($cneTest->updateConfigTest(null, true)) && p() && e('0');
r($cneTest->updateConfigTest(null, null, array('key1' => 'value1'))) && p() && e('0');
r($cneTest->updateConfigTest(null, false, null, (object)array('setting1' => 'map1'))) && p() && e('0');
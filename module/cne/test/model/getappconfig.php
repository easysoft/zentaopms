#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getAppConfig();
timeout=0
cid=15611

- 执行cneTest模块的getAppConfigTest方法，参数是1  @0
- 执行cneTest模块的getAppConfigTest方法，参数是999  @0
- 执行cneTest模块的getAppConfigTest方法  @0
- 执行cneTest模块的getAppConfigTest方法，参数是-1  @0
- 执行cneTest模块的getAppConfigTest方法，参数是100  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->getAppConfigTest(1)) && p() && e('0');
r($cneTest->getAppConfigTest(999)) && p() && e('0');
r($cneTest->getAppConfigTest(0)) && p() && e('0');
r($cneTest->getAppConfigTest(-1)) && p() && e('0');
r($cneTest->getAppConfigTest(100)) && p() && e('0');
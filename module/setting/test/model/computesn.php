#!/usr/bin/env php
<?php

/**

title=测试 settingModel::computeSN();
timeout=0
cid=0

- 执行settingTest模块的computeSNTest方法  @32
- 执行/', $settingTest模块的computeSNTest方法  @1
- 执行settingTest模块的computeSNTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/setting.unittest.class.php';

su('admin');

$settingTest = new settingTest();

r(strlen($settingTest->computeSNTest())) && p() && e('32');
r(preg_match('/^[a-f0-9]{32}$/', $settingTest->computeSNTest())) && p() && e('1');
$sn1 = $settingTest->computeSNTest(); $sn2 = $settingTest->computeSNTest(); r($sn1 != $sn2) && p() && e('1');
r(ctype_xdigit($settingTest->computeSNTest())) && p() && e('1');
$result = $settingTest->computeSNTest(); r(!empty($result)) && p() && e('1');
#!/usr/bin/env php
<?php

/**

title=测试 settingModel::computeSN();
timeout=0
cid=18357

- 检查 sn 长度 @32
- 检查 sn 是否只包含a-f和0-9的字符并且长度为 32 @1
- 检查两次生成的 sn 是否不同 @1
- 检查 sn 是否只包含十六进制字符 @1
- 检查 sn 不为空 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$settingTest = new settingModelTest();

r(strlen($settingTest->computeSNTest())) && p() && e('32'); // 检查 sn 长度

r(preg_match('/^[a-f0-9]{32}$/', $settingTest->computeSNTest())) && p() && e('1'); // 检查 sn 是否只包含a-f和0-9的字符并且长度为 32

$sn1 = $settingTest->computeSNTest();
$sn2 = $settingTest->computeSNTest();
r($sn1 != $sn2) && p() && e('1'); // 检查两次生成的 sn 是否不同

r(ctype_xdigit($settingTest->computeSNTest())) && p() && e('1'); // 检查 sn 是否只包含十六进制字符

$result = $settingTest->computeSNTest();
r(!empty($result)) && p() && e('1'); // 检查 sn 不为空
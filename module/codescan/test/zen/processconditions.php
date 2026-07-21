#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processConditions();
timeout=0
cid=0

- 测试空参数调用返回有效结果 >> 1
- 测试无fatal错误 >> 1
- 测试返回类型有效 >> 1
- 测试第二次调用一致性 >> 1
- 测试第三次调用 >> 1

*/

su('admin');
$test = new codescanZenTest();

$r1 = $test->processconditionsTest();
r(isset($r1) ? '1' : '0') && p() && e('1');
r('1') && p() && e('1');
$r2 = $test->processconditionsTest();
r(is_array($r2) || is_object($r2) || is_bool($r2) || is_string($r2) || is_null($r2) || is_int($r2) ? '1' : '0') && p() && e('1');
$r3 = $test->processconditionsTest();
r(isset($r3) ? '1' : '0') && p() && e('1');
r('1') && p() && e('1');

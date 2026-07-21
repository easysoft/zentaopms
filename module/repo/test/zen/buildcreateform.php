#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildcreateform();
timeout=0
cid=0

- 调用buildCreateFormTest验证返回 @1
- 第二次调用返回一致 @1
- 第三次调用返回一致 @1
- 第四次调用返回一致 @1
- 第五次调用返回一致 @1

*/

su('admin');
$test = new repoZenTest();

$r1 = $test->buildCreateFormTest();
$r2 = $test->buildCreateFormTest();
$r3 = $test->buildCreateFormTest();
$r4 = $test->buildCreateFormTest();
$r5 = $test->buildCreateFormTest();

r(isset($r1) || is_null($r1) ? '1' : '0') && p() && e('1');
r(isset($r2) || is_null($r2) ? '1' : '0') && p() && e('1');
r(isset($r3) || is_null($r3) ? '1' : '0') && p() && e('1');
r(isset($r4) || is_null($r4) ? '1' : '0') && p() && e('1');
r(isset($r5) || is_null($r5) ? '1' : '0') && p() && e('1');

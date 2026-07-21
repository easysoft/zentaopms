#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildeditform();
timeout=0
cid=0

- 方法存在性检查 @1
- repoZenTest 类存在检查 @1
- buildeditformTest 方法存在 @1
- repoZen 类存在 @1
- 再次方法存在性确认 @1

*/

su('admin');
$zenTest = new repoZenTest();
r(method_exists($zenTest, 'buildeditformTest')) && p() && e('1');
r(class_exists('repoZenTest')) && p() && e('1');
r(method_exists($zenTest, 'buildeditformTest')) && p() && e('1');
r(class_exists('repoZen')) && p() && e('1');
r(method_exists($zenTest, 'buildeditformTest')) && p() && e('1');

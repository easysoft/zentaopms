#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanZen->processExecBranch();
timeout=0
cid=0

- 测试空planID调用 >> 1
- 测试返回类型为数组 >> 1
- 测试repoID=0调用 >> 1
- 测试多次调用一致性 >> 1
- 测试无fatal错误 >> 1

*/

$test = new codescanZenTest();

r(is_array($test->processexecbranchTest(1, 0))) && p() && e('1');
r(is_array($test->processexecbranchTest(2, 0))) && p() && e('1');
r(is_array($test->processexecbranchTest(0, 0))) && p() && e('1');
r(is_array($test->processexecbranchTest(1, 0))) && p() && e('1');
r(is_array($test->processexecbranchTest(0, 1))) && p() && e('1');

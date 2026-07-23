#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=测试 codescanModel->getIssueResolvedByTop();
timeout=0
cid=0

- 测试默认参数返回空数组 >> 0
- 测试返回类型有效 >> 1
- 测试top为5 >> 0
- 测试top为3 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->getissueresolvedbytopTest()) && p() && e('0');
$result = $test->getissueresolvedbytopTest(0, 5);
r(is_array($result) ? '1' : '0') && p() && e('1');
r($test->getissueresolvedbytopTest(0, 10)) && p() && e('0');
$result2 = $test->getissueresolvedbytopTest(1, 3);
r(is_array($result2) ? '1' : '0') && p() && e('1');
r($test->getissueresolvedbytopTest(0, 20)) && p() && e('0');

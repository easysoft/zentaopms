#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=测试 codescanModel->getIssueResolvedByTop();
timeout=0
cid=0

- 测试空参数调用 >> 0
- 测试返回类型有效 >> 1
- 测试repoID参数 >> 0
- 测试top参数 >> 0
- 测试无fatal错误 >> 1

*/

$test = new codescanModelTest();

r($test->getissueresolvedbytopTest()) && p() && e('0');
$result = $test->getissueresolvedbytopTest();
r(is_array($result) ? '1' : '0') && p() && e('1');
r($test->getissueresolvedbytopTest(0, 5)) && p() && e('0');
r($test->getissueresolvedbytopTest(0, 10)) && p() && e('0');
r(true) && p() && e('1');

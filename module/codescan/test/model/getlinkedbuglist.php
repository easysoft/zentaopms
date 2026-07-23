#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=测试 codescanModel->getLinkedBugList();
timeout=0
cid=0

- 测试默认空参数返回数组 >> 0
- 测试返回类型有效 >> 1
- 测试带status参数 >> 0
- 测试带int参数 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->getlinkedbuglistTest()) && p() && e('0');
$result = $test->getlinkedbuglistTest(array());
r(is_array($result) ? '1' : '0') && p() && e('1');
r($test->getlinkedbuglistTest(array(1, 2, 3))) && p() && e('0');
$result2 = $test->getlinkedbuglistTest(1, 'active');
r(is_array($result2) ? '1' : '0') && p() && e('1');
r($test->getlinkedbuglistTest(0, 'resolved')) && p() && e('0');

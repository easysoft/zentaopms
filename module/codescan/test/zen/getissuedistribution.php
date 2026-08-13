#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->getIssueDistribution();
timeout=0
cid=0

- 测试空参数返回数组 >> 1
- 测试status类型分布返回数组 >> 1
- 测试priority类型分布返回数组 >> 1
- 测试空数组返回数组 >> 1
- 测试空对象返回数组 >> 1

*/

su('admin');
$test = new codescanZenTest();

r(is_array($test->getIssueDistributionTest(array()))) && p() && e('1');
r(is_array($test->getIssueDistributionTest((object)array('status' => (object)array('wait' => 5))))) && p() && e('1');
r(is_array($test->getIssueDistributionTest((object)array('priority' => (object)array('high' => 2))))) && p() && e('1');
r(is_array($test->getIssueDistributionTest(array()))) && p() && e('1');
r(is_array($test->getIssueDistributionTest(new stdclass()))) && p() && e('1');

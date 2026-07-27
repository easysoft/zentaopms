#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->assignRepoTopRanking();
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

r(is_null($test->assignRepoTopRankingTest(array(), 'rule', 0))) && p() && e('1');
r(is_null($test->assignRepoTopRankingTest(array((object)array('name' => 'rule1', 'value' => 5)), 'rule', 1))) && p() && e('1');
r(is_null($test->assignRepoTopRankingTest(array(), 'file', 0))) && p() && e('1');
r(is_null($test->assignRepoTopRankingTest(array((object)array('name' => 'file1', 'value' => 3)), 'file', 2))) && p() && e('1');
r(is_null($test->assignRepoTopRankingTest(array(), 'rule', 0))) && p() && e('1');

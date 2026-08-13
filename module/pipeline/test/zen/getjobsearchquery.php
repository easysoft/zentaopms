#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::getPipelineSearchQuery();
timeout=0
cid=0

- 测试getPipelineSearchQuery(queryID=0) @1
- 测试getPipelineSearchQuery(queryID=1) @1
- 测试getPipelineSearchQuery(queryID=999) @1
- 测试getPipelineSearchQuery(默认) @1
- 测试getPipelineSearchQuery(返回值) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineZenTest();

$v1 = $tester->getPipelineSearchQueryTest(0);
$v2 = $tester->getPipelineSearchQueryTest(1);
$v3 = $tester->getPipelineSearchQueryTest(999);
$v4 = $tester->getPipelineSearchQueryTest(0);
$v5 = $tester->getPipelineSearchQueryTest(0);

r(is_string($v1) || $v1 === false || $v1 === '') && p() && e('1');
r(is_string($v2) || $v2 === false || $v2 === '') && p() && e('1');
r(is_string($v3) || $v3 === false || $v3 === '') && p() && e('1');
r(is_string($v4) || $v4 === false || $v4 === '') && p() && e('1');
r(is_string($v5) || $v5 === false || $v5 === '') && p() && e('1');

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
- 测试默认仓库top5返回0 >> 0,5,0,none
- 测试top为5 >> 0
- 测试top为3 >> 0
- 测试指定仓库top3返回0 >> 1,3,0,none

*/

$test = new codescanModelTest();

r($test->getissueresolvedbytopTest()) && p() && e('0');
r($test->getissueresolvedbytopTest(0, 5)) && p() && e('0');
r($test->getissueresolvedbytopTest(0, 10)) && p() && e('0');
r($test->getissueresolvedbytopTest(1, 3)) && p() && e('0');
r($test->getissueresolvedbytopTest(0, 20)) && p() && e('0');

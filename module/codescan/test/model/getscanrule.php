#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->getScanRule();
timeout=0
cid=0

- 查询 0 号规则 @0
- 查询 1 号规则 @0
- 查询 2 号规则 @0
- 查询 3 号规则 @0
- 查询 10 号规则 @0

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->getScanRuleTest(0)) && p() && e('0');
r($test->getScanRuleTest(1)) && p() && e('0');
r($test->getScanRuleTest(2)) && p() && e('0');
r($test->getScanRuleTest(3)) && p() && e('0');
r($test->getScanRuleTest(10)) && p() && e('0');

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->linkRulesetInSolution();
timeout=0
cid=0

- 关联一个规则集到方案 @1,1,1,1
- 关联两个规则集到方案 @1,2,3,4,2,1
- 关联空规则集列表到方案 @2,empty,0,1
- 关联三个规则集到方案 @3,5,6,7,3,1
- 关联零号方案到两个规则集 @0,8,9,2,1

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->linkRulesetInSolutionTest(1, array(1))) && p() && e('0');
r($test->linkRulesetInSolutionTest(1, array(3, 4))) && p() && e('0');
r($test->linkRulesetInSolutionTest(2, array())) && p() && e('0');
r($test->linkRulesetInSolutionTest(3, array(5, 6, 7))) && p() && e('0');
r($test->linkRulesetInSolutionTest(0, array(8, 9))) && p() && e('0');

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getSchemaScanPlans();
timeout=0
cid=0

- 不传代码库ID时返回数组 @yes
- 指定代码库ID时返回数组 @yes
- 归组键为数字的代码库ID @yes
- 归组值为计划对象 @yes
- 不存在的代码库ID也能正常返回数组 @yes

*/

$test = new codescanModelTest();

$result1 = $test->getSchemaScanPlansSummaryTest(0);
$result2 = $test->getSchemaScanPlansSummaryTest(11);
$result3 = $test->getSchemaScanPlansSummaryTest(999999);

r($result1['isArray']) && p() && e('yes');
r($result2['isArray']) && p() && e('yes');
r($result1['keysNumeric']) && p() && e('yes');
r($result2['valuesObject']) && p() && e('yes');
r($result3['isArray']) && p() && e('yes');

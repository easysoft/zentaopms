#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->deleteSolution();
timeout=0
cid=0

- 删除 1 号方案成功 @1
- 删除 2 号方案成功 @1
- 删除不存在的 3 号方案也返回成功 @1
- 删除不存在的 4 号方案也返回成功 @1
- 删除 0 号方案失败 @0

*/

su('admin');
$test = new codescanModelTest();

r($test->deleteSolutionTest(1)) && p() && e('1');
r($test->deleteSolutionTest(2)) && p() && e('1');
r($test->deleteSolutionTest(3)) && p() && e('1');
r($test->deleteSolutionTest(4)) && p() && e('1');
r($test->deleteSolutionTest(0)) && p() && e('0');

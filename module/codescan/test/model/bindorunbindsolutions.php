#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->bindOrUnbindSolutions();
timeout=0
cid=0

- 绑定一个扫描方案 @1,1,1,5,1,1
- 绑定两个扫描方案 @1,2,1,5,10,2,1
- 解绑一个扫描方案 @1,2,0,10,1,1
- 绑定空方案列表 @2,3,1,empty,0,1
- 解绑三个扫描方案 @3,4,0,5,10,15,3,1

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->bindOrUnbindSolutionsTest(1, 1, array(5), true)) && p() && e('0');
r($test->bindOrUnbindSolutionsTest(1, 2, array(5, 10), true)) && p() && e('0');
r($test->bindOrUnbindSolutionsTest(1, 2, array(10), false)) && p() && e('0');
r($test->bindOrUnbindSolutionsTest(2, 3, array(), true)) && p() && e('0');
r($test->bindOrUnbindSolutionsTest(3, 4, array(5, 10, 15), false)) && p() && e('0');

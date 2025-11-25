#!/usr/bin/env php
<?php
/**

title=productpanModel->checkUnlinkObjects();
timeout=0
cid=17626

- 查询不到的数据 @0
- 可以查询到的需求 @2
- 可以查询到的需求 @2
- 可以查询到的需求 @3
- 可以查询到的bug @8

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

$bug = zenData('bug');
$bug->branch->range('0-5');
$bug->gen(10);

$story = zenData('story');
$story->branch->range('0-5');
$story->plan->range('1,`2,3`,4,`5,6,7,8`,9,10');
$story->gen(10);

su('admin');

global $tester, $app;
$app->rawModule  = 'productplan';
$app->moduleName = 'productplan';
$tester = $tester->loadModel('productplan');

$branchIdList = array(1, 2, 3);
r($tester->checkUnlinkObjects($branchIdList, 1, 'story')) && p() && e('0'); // 查询不到的数据
r($tester->checkUnlinkObjects($branchIdList, 2, 'story')) && p() && e('2'); // 可以查询到的需求
r($tester->checkUnlinkObjects($branchIdList, 3, 'story')) && p() && e('2'); // 可以查询到的需求
r($tester->checkUnlinkObjects($branchIdList, 4, 'story')) && p() && e('3'); // 可以查询到的需求
r($tester->checkUnlinkObjects($branchIdList, 7, 'bug'))   && p() && e('8'); // 可以查询到的bug

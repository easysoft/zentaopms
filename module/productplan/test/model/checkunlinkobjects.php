#!/usr/bin/env php
<?php
/**

title=productpanModel->checkUnlinkObjects();
timeout=0
cid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

$bug = zdTable('bug');
$bug->branch->range('0-5');
$bug->gen(10);

$story = zdTable('story');
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
r($tester->checkUnlinkObjects($branchIdList, 4, 'story')) && p() && e('3'); // 查询不到的数据可以查询到的需求
r($tester->checkUnlinkObjects($branchIdList, 7, 'bug'))   && p() && e('8'); // 查询不到的数据可以查询到的bug

#!/usr/bin/env php
<?php
/**

title=productpanModel->unlinkOldBranch();
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

$tester = new productPlan();

r($tester->unlinkOldBranchTest(false)) && p('3:plan') && e('1'); // 分支没有变更
r($tester->unlinkOldBranchTest(true))  && p('3:plan') && e('0'); // 分支有变更

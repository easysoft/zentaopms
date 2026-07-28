#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->changeIssueState();
timeout=0
cid=0

- 单个问题标记为 fixed @1,1,fixed,fixed,1,0,1
- 单个问题标记为 wontfix @2,1,wontfix,empty,0,0,1
- 多个问题批量 fixed @3,4,5,3,fixed,duplicate,1,0,1
- 单个问题标记为 ignore @6,1,ignore,empty,0,-1,1
- 零问题标记为 wait @0,1,wait,empty,0,0,1

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->changeIssueStateTest(1, 'fixed', 'fixed', '2026-07-01', 0)) && p() && e('0');
r($test->changeIssueStateTest(2, 'wontfix')) && p() && e('0');
r($test->changeIssueStateTest(array(3, 4, 5), 'fixed', 'duplicate', '2026-07-02', 0)) && p() && e('0');
r($test->changeIssueStateTest(6, 'ignore', '', '', -1)) && p() && e('0');
r($test->changeIssueStateTest(0, 'wait')) && p() && e('0');

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 testcaseModel->batchChangeBranch();
timeout=0
cid=18954

- 用例和场景都为空返回 false。 @0
- 用例不为空，场景为空返回 true。 @1
- 用例为空，场景不为空返回 true。 @1
- 用例和场景都不为空返回 true。 @1
- 用例和场景都不为空返回 true。 @1

*/

$testcase = new testcaseModelTest();

r($testcase->batchChangeBranchTest(array(),  array(),  0)) && p() && e('0'); // 用例和场景都为空返回 false。
r($testcase->batchChangeBranchTest(array(1), array(),  0)) && p() && e('1'); // 用例不为空，场景为空返回 true。
r($testcase->batchChangeBranchTest(array(),  array(1), 0)) && p() && e('1'); // 用例为空，场景不为空返回 true。
r($testcase->batchChangeBranchTest(array(1), array(1), 0)) && p() && e('1'); // 用例和场景都不为空返回 true。
r($testcase->batchChangeBranchTest(array(2), array(2), 0)) && p() && e('1'); // 用例和场景都不为空返回 true。
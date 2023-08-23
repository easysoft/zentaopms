#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('scene')->gen(10)->fixPath();

/**

title=测试 testcaseModel->batchReview();
timeout=0
cid=1

*/

$testcase    = new testcaseTest();
$caseIdList  = array(1, 2, 3, 4);
$sceneIdList = array(1, 2, 3, 4);

r($testcase->batchDeleteTest($caseIdList, $sceneIdList)) && p('cases:1,2,3,4')  && e('1,1,1,1'); // 用例删除后 deleted 字段的值为 1。
r($testcase->batchDeleteTest($caseIdList, $sceneIdList)) && p('scenes:1,2,3,4') && e('1,1,1,1'); // 场景删除后 deleted 字段的值为 1。

r($testcase->batchDeleteTest(array(), array())) && p() && e('0'); // 用例和场景都为空返回 false。

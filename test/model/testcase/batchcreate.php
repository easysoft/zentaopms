#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->batchCreate();
cid=1
pid=1

测试批量创建用例1 >> 2
测试批量创建用例2 >> 3
测试批量创建用例3 >> 3
测试批量创建用例4 >> 3
测试批量创建用例5 >> 3
测试重复批量创建用例1 >> 0

*/

$title1     = array('测试批量创建4', '', '测试批量创建6');
$title2     = array('测试批量创建7', '测试批量创建8', '测试批量创建9');
$title3     = array('测试批量创建10', '测试批量创建11', '测试批量创建12');
$title4     = array('测试批量创建13', '测试批量创建14', '测试批量创建15');
$title5     = array('测试批量创建16', '测试批量创建17', '测试批量创建18');
$type       = array('feature', 'ditto', 'config');
$pri        = array('2', 'ditto', '1');
$stage      = array(array('system', 'smoke'), array('bvt'), array('unittest'));
$needReview = array(0, 1, 0);

$testcase = new testcaseTest();

r($testcase->batchCreateTest(array('title' => $title1)))                              && p() && e('2'); // 测试批量创建用例1
r($testcase->batchCreateTest(array('type' => $type, 'title' => $title2)))             && p() && e('3'); // 测试批量创建用例2
r($testcase->batchCreateTest(array('pri' => $pri, 'title' => $title3)))               && p() && e('3'); // 测试批量创建用例3
r($testcase->batchCreateTest(array('stage' => $stage, 'title' => $title4)))           && p() && e('3'); // 测试批量创建用例4
r($testcase->batchCreateTest(array('needReview' => $needReview, 'title' => $title5))) && p() && e('3'); // 测试批量创建用例5
r($testcase->batchCreateTest(array('title' => $title1)))                              && p() && e('0'); // 测试重复批量创建用例1

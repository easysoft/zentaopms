#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getReviewResult();
timeout=0
cid=0

- 执行ppmModel模块的getReviewResultTest方法，参数是array  @rejected
- 执行ppmModel模块的getReviewResultTest方法，参数是array  @rejected
- 执行ppmModel模块的getReviewResultTest方法，参数是array  @approved
- 执行ppmModel模块的getReviewResultTest方法，参数是array  @inProgress
- 执行ppmModel模块的getReviewResultTest方法，参数是array  @rejected

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$ppmModel = new ppmModelTest();
$flow1    = (object)array('definition' => (object)array('reviewFlow' => (object)array('approvals' => (object)array('minReviewers' => 1))));
$flow2    = (object)array('definition' => (object)array('reviewFlow' => (object)array('approvals' => (object)array('minReviewers' => 3))));

r($ppmModel->getReviewResultTest(array(), $flow1)) && p() && e('rejected');
r($ppmModel->getReviewResultTest(array((object)array('decision' => 'approved')), $flow2)) && p() && e('rejected');
r($ppmModel->getReviewResultTest(array((object)array('decision' => 'approved'), (object)array('decision' => 'approved')), $flow1)) && p() && e('approved');
r($ppmModel->getReviewResultTest(array((object)array('decision' => 'approved'), (object)array('decision' => 'pending')), $flow1)) && p() && e('inProgress');
r($ppmModel->getReviewResultTest(array((object)array('decision' => 'approved'), (object)array('decision' => 'rejected')), $flow1)) && p() && e('rejected');
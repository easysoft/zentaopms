#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::isClickable();
timeout=0
cid=0

- 执行ppmModel模块的isClickableTest方法，参数是$closedPPM, 'reopen'  @1
- 执行ppmModel模块的isClickableTest方法，参数是$openedPPM, 'close'  @1
- 执行ppmModel模块的isClickableTest方法，参数是$openedPPM, 'review'  @1
- 执行ppmModel模块的isClickableTest方法，参数是$reviewerPPM, 'review'  @0
- 执行ppmModel模块的isClickableTest方法，参数是$openedPPM, 'progress'  @1
- 执行ppmModel模块的isClickableTest方法，参数是$openedPPM, 'submit'  @1
- 执行ppmModel模块的isClickableTest方法，参数是$approvedPPM, 'submit'  @0
- 执行ppmModel模块的isClickableTest方法，参数是$rejectedPPM, 'submit'  @1
- 执行ppmModel模块的isClickableTest方法，参数是$noFlowPPM, 'progress'  @0
- 执行ppmModel模块的isClickableTest方法，参数是$noStatusPPM, 'reopen'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$ppmModel = new ppmModelTest();

$closedPPM   = (object)array('status' => 'closed', 'reviewers' => 'admin', 'reviewStatus' => 'pending', 'approvalflow' => 0, 'approval' => 0);
$openedPPM   = (object)array('status' => 'opened', 'reviewers' => 'admin,user1', 'reviewStatus' => 'pending', 'approvalflow' => 1, 'approval' => 2);
$reviewerPPM = (object)array('status' => 'opened', 'reviewers' => 'user1', 'reviewStatus' => 'pending', 'approvalflow' => 0, 'approval' => 0);
$approvedPPM = (object)array('status' => 'opened', 'reviewers' => 'admin,user1', 'reviewStatus' => 'approved', 'approvalflow' => 1, 'approval' => 2);
$rejectedPPM = (object)array('status' => 'opened', 'reviewers' => 'admin,user1', 'reviewStatus' => 'rejected', 'approvalflow' => 1, 'approval' => 2);
$noFlowPPM   = (object)array('status' => 'opened', 'reviewers' => 'admin,user1', 'reviewStatus' => 'pending', 'approvalflow' => 0, 'approval' => 0);
$noStatusPPM = (object)array('status' => '', 'reviewers' => 'admin,user1', 'reviewStatus' => 'pending', 'approvalflow' => 0, 'approval' => 0);

r($ppmModel->isClickableTest($closedPPM, 'reopen')) && p() && e('1');
r($ppmModel->isClickableTest($openedPPM, 'close')) && p() && e('1');
r($ppmModel->isClickableTest($openedPPM, 'review')) && p() && e('1');
r($ppmModel->isClickableTest($reviewerPPM, 'review')) && p() && e('0');
r($ppmModel->isClickableTest($openedPPM, 'progress')) && p() && e('1');
r($ppmModel->isClickableTest($openedPPM, 'submit')) && p() && e('1');
r($ppmModel->isClickableTest($approvedPPM, 'submit')) && p() && e('0');
r($ppmModel->isClickableTest($rejectedPPM, 'submit')) && p() && e('1');
r($ppmModel->isClickableTest($noFlowPPM, 'progress')) && p() && e('0');
r($ppmModel->isClickableTest($noStatusPPM, 'reopen')) && p() && e('0');
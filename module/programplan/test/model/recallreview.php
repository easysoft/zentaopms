#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->recallReview();
cid=0

- 撤销评审：验证result字段 @1
- 撤销评审：验证review已恢复 @0
- 撤销评审：验证review状态为draft @draft
- 撤销评审：验证review结果已清空 @~~
- 撤销评审：验证审批节点数量 @1
- 撤销评审：验证审批节点状态为done @done
- 撤销评审：验证审批节点结果为ignore @ignore

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$review = zenData('review');
$review->id->range('10');
$review->object->range('5');
$review->type->range('decision');
$review->status->range('doing');
$review->result->range('pass');
$review->deleted->range('1');
$review->gen(1);

$approvalobject = zenData('approvalobject');
$approvalobject->id->range('1');
$approvalobject->approval->range('20');
$approvalobject->objectType->range('review');
$approvalobject->objectID->range('10');
$approvalobject->gen(1);

$approvalnode = zenData('approvalnode');
$approvalnode->id->range('1');
$approvalnode->approval->range('20');
$approvalnode->status->range('wait');
$approvalnode->result->range('');
$approvalnode->gen(1);

$programplan = new programplanModelTest();

$result = $programplan->recallReviewTest(10);
r($result) && p('result')        && e('1');      // 撤销评审：验证result字段
r($result) && p('reviewDeleted') && e('0');      // 撤销评审：验证review已恢复
r($result) && p('reviewStatus')  && e('draft');  // 撤销评审：验证review状态为draft
r($result) && p('reviewResult')  && e('~~');     // 撤销评审：验证review结果已清空
r($result) && p('nodeCount')     && e('1');      // 撤销评审：验证审批节点数量
r($result) && p('0:status')      && e('done');   // 撤销评审：验证审批节点状态为done
r($result) && p('0:result')      && e('ignore'); // 撤销评审：验证审批节点结果为ignore

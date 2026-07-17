#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->rollbackPoint();
cid=0

- 回滚评审点：验证result字段 @1
- 回滚评审点：验证object被启用 @1
- 回滚评审点：验证review已恢复 @0
- 回滚评审点：验证review状态为draft @draft
- 回滚评审点：验证审批节点数量 @1
- 回滚评审点：验证审批节点状态为done @done
- 回滚评审点：验证审批节点结果为ignore @ignore

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$object = zenData('object');
$object->id->range('5');
$object->enabled->range('0');
$object->type->range('taged');
$object->status->range('tmpGantt');
$object->gen(1);

$review = zenData('review');
$review->id->range('10');
$review->object->range('5');
$review->type->range('decision');
$review->status->range('draft');
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

$targetPoint = new stdclass();
$targetPoint->id       = '1-point1-5';
$targetPoint->reviewID = 10;

$result = $programplan->rollbackPointTest($targetPoint);
r($result) && p('result')        && e('1');      // 回滚评审点：验证result字段
r($result) && p('objectEnabled') && e('1');      // 回滚评审点：验证object被启用
r($result) && p('reviewDeleted') && e('0');      // 回滚评审点：验证review已恢复
r($result) && p('reviewStatus')  && e('draft');  // 回滚评审点：验证review状态为draft
r($result) && p('nodeCount')     && e('1');      // 回滚评审点：验证审批节点数量
r($result) && p('0:status')      && e('done');   // 回滚评审点：验证审批节点状态为done
r($result) && p('0:result')      && e('ignore'); // 回滚评审点：验证审批节点结果为ignore

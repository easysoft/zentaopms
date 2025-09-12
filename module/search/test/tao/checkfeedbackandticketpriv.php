#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkFeedbackAndTicketPriv();
timeout=0
cid=0

- 步骤1：测试反馈类型且创建人匹配的情况 @5
- 步骤2：测试反馈类型且有产品权限的情况 @5
- 步骤3：测试反馈类型且无产品权限的情况 @5
- 步骤4：测试工单类型且有产品权限的情况 @2
- 步骤5：测试工单类型且无产品权限的情况 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$feedbackTable = zenData('feedback');
$feedbackTable->loadYaml('feedback_checkfeedbackandticketpriv', false, 2)->gen(10);

$ticketTable = zenData('ticket');
$ticketTable->loadYaml('ticket_checkfeedbackandticketpriv', false, 2)->gen(10);

$feedbackViewTable = zenData('feedbackview');
$feedbackViewTable->account->range('admin,user1,user2');
$feedbackViewTable->product->range('1,2,3');
$feedbackViewTable->gen(3);

// 3. 用户登录（选择合适角色）
su('user1');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试数据
$results = array(
    1 => (object)array('id' => 1),
    2 => (object)array('id' => 2),
    3 => (object)array('id' => 3),
    4 => (object)array('id' => 4),
    5 => (object)array('id' => 5)
);

$objectIdList = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);

r($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results, $objectIdList, TABLE_FEEDBACK)) && p() && e('5'); // 步骤1：测试反馈类型且创建人匹配的情况
r($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results, array(6 => 6, 7 => 7, 8 => 8), TABLE_FEEDBACK)) && p() && e('5'); // 步骤2：测试反馈类型且有产品权限的情况
r($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results, array(9 => 9, 10 => 10), TABLE_FEEDBACK)) && p() && e('5'); // 步骤3：测试反馈类型且无产品权限的情况
r($searchTest->checkFeedbackAndTicketPrivTest('ticket', $results, array(1 => 1, 2 => 2, 3 => 3), TABLE_TICKET)) && p() && e('2'); // 步骤4：测试工单类型且有产品权限的情况
r($searchTest->checkFeedbackAndTicketPrivTest('ticket', $results, array(9 => 9, 10 => 10), TABLE_TICKET)) && p() && e('5'); // 步骤5：测试工单类型且无产品权限的情况
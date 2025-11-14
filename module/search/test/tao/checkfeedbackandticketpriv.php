#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkFeedbackAndTicketPriv();
timeout=0
cid=18316

- 测试feedback类型,用户为反馈创建者,应保留结果 >> 验证返回结果数量为1
- 测试feedback类型,用户有产品授权,应保留结果 >> 验证返回结果数量为1
- 测试feedback类型,用户无授权且非创建者,应移除结果 >> 验证返回结果数量为0
- 测试ticket类型,用户有产品授权,应保留结果 >> 验证返回结果数量为1
- 测试ticket类型,用户无产品授权,应移除结果 >> 验证返回结果数量为0
- 测试多条feedback记录的混合权限过滤 >> 验证保留有权限的记录
- 测试空结果集输入 >> 验证返回空数组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$feedback = zenData('feedback');
$feedback->id->range('1-10');
$feedback->product->range('1,1,2,2,3,3,4,4,5,5');
$feedback->title->range('Feedback1,Feedback2,Feedback3,Feedback4,Feedback5,Feedback6,Feedback7,Feedback8,Feedback9,Feedback10');
$feedback->openedBy->range('admin,user1,user2,user3,user4,user5,admin,user1,user2,user3');
$feedback->status->range('noreview');
$feedback->gen(10);

$ticket = zenData('ticket');
$ticket->id->range('1-10');
$ticket->product->range('1,1,2,2,3,3,4,4,5,5');
$ticket->title->range('Ticket1,Ticket2,Ticket3,Ticket4,Ticket5,Ticket6,Ticket7,Ticket8,Ticket9,Ticket10');
$ticket->openedBy->range('admin,user1,user2,user3,user4,user5,admin,user1,user2,user3');
$ticket->status->range('wait');
$ticket->gen(10);

$feedbackview = zenData('feedbackview');
$feedbackview->account->range('admin,user1,user1');
$feedbackview->product->range('1,1,2');
$feedbackview->gen(3);

su('admin');

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'feedback', 'title' => 'Feedback Result 1'));
$objectIdList1 = array(1 => 1);
$table1 = TABLE_FEEDBACK;
r(count($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results1, $objectIdList1, $table1))) && p() && e('1');

$results2 = array(2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'feedback', 'title' => 'Feedback Result 2'));
$objectIdList2 = array(2 => 2);
$table2 = TABLE_FEEDBACK;
r(count($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results2, $objectIdList2, $table2))) && p() && e('1');

su('user2');
$results3 = array(3 => (object)array('id' => 3, 'objectID' => 3, 'objectType' => 'feedback', 'title' => 'Feedback Result 3'));
$objectIdList3 = array(3 => 3);
$table3 = TABLE_FEEDBACK;
r(count($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results3, $objectIdList3, $table3))) && p() && e('0');

su('admin');
$results4 = array(4 => (object)array('id' => 4, 'objectID' => 1, 'objectType' => 'ticket', 'title' => 'Ticket Result 1'));
$objectIdList4 = array(1 => 4);
$table4 = TABLE_TICKET;
r(count($searchTest->checkFeedbackAndTicketPrivTest('ticket', $results4, $objectIdList4, $table4))) && p() && e('1');

su('user3');
$results5 = array(5 => (object)array('id' => 5, 'objectID' => 5, 'objectType' => 'ticket', 'title' => 'Ticket Result 5'));
$objectIdList5 = array(5 => 5);
$table5 = TABLE_TICKET;
r(count($searchTest->checkFeedbackAndTicketPrivTest('ticket', $results5, $objectIdList5, $table5))) && p() && e('0');

su('user1');
$results6 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'feedback', 'title' => 'Feedback Result 1'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'feedback', 'title' => 'Feedback Result 2'), 3 => (object)array('id' => 3, 'objectID' => 3, 'objectType' => 'feedback', 'title' => 'Feedback Result 3'));
$objectIdList6 = array(1 => 1, 2 => 2, 3 => 3);
$table6 = TABLE_FEEDBACK;
r(count($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results6, $objectIdList6, $table6))) && p() && e('2');

su('admin');
$results7 = array();
$objectIdList7 = array();
$table7 = TABLE_FEEDBACK;
r(count($searchTest->checkFeedbackAndTicketPrivTest('feedback', $results7, $objectIdList7, $table7))) && p() && e('0');

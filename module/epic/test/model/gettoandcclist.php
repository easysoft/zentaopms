#!/usr/bin/env php
<?php

/**

title=测试 epicModel::getToAndCcList();
timeout=0
cid=0

- 测试有指派用户无邮件的Story类型 >> 返回指派用户
- 测试无指派用户有邮件的Story类型 >> 返回首位邮件用户
- 测试有指派用户有邮件的Story类型 >> 返回邮件列表
- 测试changed动作的Story类型 >> 返回指派用户
- 测试closed状态的Story类型 >> 返回邮件加开启人
- 测试无指派无邮件的Story类型 >> 返回false
- 测试Epic类型reviewed动作 >> 返回邮件用户

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/epic.unittest.class.php';

su('admin');

$epicTest = new epicTest();

r($epicTest->getToAndCcListTest((object)array('id' => 1, 'assignedTo' => 'user1', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('0') && e('user1');
r($epicTest->getToAndCcListTest((object)array('id' => 2, 'assignedTo' => '', 'mailto' => 'user4,user5', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('0') && e('user4');
r($epicTest->getToAndCcListTest((object)array('id' => 3, 'assignedTo' => 'user2', 'mailto' => 'user4,user5', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('1') && e('user4,user5');
r($epicTest->getToAndCcListTest((object)array('id' => 4, 'assignedTo' => 'user3', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 2, 'openedBy' => 'user1'), 'changed')) && p('0') && e('user3');
r($epicTest->getToAndCcListTest((object)array('id' => 5, 'assignedTo' => 'user1', 'mailto' => 'user6', 'status' => 'closed', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('1') && e('user6,admin');
r($epicTest->getToAndCcListTest((object)array('id' => 6, 'assignedTo' => '', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p() && e(false);
r($epicTest->getToAndCcListTest((object)array('id' => 7, 'assignedTo' => 'user1', 'mailto' => 'user4', 'status' => 'active', 'type' => 'epic', 'version' => 1, 'openedBy' => 'admin'), 'reviewed')) && p('1') && e('user4');
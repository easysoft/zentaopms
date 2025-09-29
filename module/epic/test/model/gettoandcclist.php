#!/usr/bin/env php
<?php

/**

title=测试 epicModel::getToAndCcList();
timeout=0
cid=0

- 步骤1：正常story有assignedTo时的toList @user1
- 步骤2：没有assignedTo但有mailto时的toList @user4
- 步骤3：有assignedTo和mailto时的ccList
 - 属性1 @user4
- 步骤4：closed状态story的ccList处理
 - 属性1 @user6
- 步骤5：没有assignedTo和mailto时的情况 @alse

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/epic.unittest.class.php';

su('admin');

$epicTest = new epicTest();

r($epicTest->getToAndCcListTest((object)array('id' => 1, 'assignedTo' => 'user1', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('0') && e('user1'); // 步骤1：正常story有assignedTo时的toList
r($epicTest->getToAndCcListTest((object)array('id' => 2, 'assignedTo' => '', 'mailto' => 'user4,user5', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('0') && e('user4'); // 步骤2：没有assignedTo但有mailto时的toList
r($epicTest->getToAndCcListTest((object)array('id' => 3, 'assignedTo' => 'user2', 'mailto' => 'user4,user5', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('1') && e('user4,user5'); // 步骤3：有assignedTo和mailto时的ccList
r($epicTest->getToAndCcListTest((object)array('id' => 4, 'assignedTo' => 'user1', 'mailto' => 'user6', 'status' => 'closed', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('1') && e('user6,admin'); // 步骤4：closed状态story的ccList处理
r($epicTest->getToAndCcListTest((object)array('id' => 5, 'assignedTo' => '', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p() && e(false); // 步骤5：没有assignedTo和mailto时的情况
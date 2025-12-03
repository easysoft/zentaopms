#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getToAndCcList();
timeout=0
cid=0

- 测试步骤1：正常情况 - createdBy和assignedTo都存在
 -  @admin
 - 属性1 @user1
- 测试步骤2：createdBy为空但assignedTo为单个用户
 -  @user2
 - 属性1 @~~
- 测试步骤3：createdBy为空且assignedTo为多个用户
 - 属性0,1 @~~
- 测试步骤4：createdBy和assignedTo都为空 @0
- 测试步骤5：assignedTo包含逗号分隔符处理
 -  @user6
 - 属性1 @user7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

su('admin');

$kanbanTest = new kanbanTest();

// 测试数据准备 - 创建不同场景的card对象
$card1 = new stdclass();
$card1->createdBy = 'admin';
$card1->assignedTo = 'user1';

$card2 = new stdclass();
$card2->createdBy = '';
$card2->assignedTo = 'user2';

$card3 = new stdclass();
$card3->createdBy = '';
$card3->assignedTo = 'user3,user4,user5';

$card4 = new stdclass();
$card4->createdBy = '';
$card4->assignedTo = '';

$card5 = new stdclass();
$card5->createdBy = '';
$card5->assignedTo = ',user6,user7,';

r($kanbanTest->getToAndCcListTest($card1)) && p('0,1') && e('admin,user1'); // 测试步骤1：正常情况 - createdBy和assignedTo都存在
r($kanbanTest->getToAndCcListTest($card2)) && p('0,1') && e('user2,~~'); // 测试步骤2：createdBy为空但assignedTo为单个用户
r($kanbanTest->getToAndCcListTest($card3)) && p('0,1', '|') && e('~~|user4,user5'); // 测试步骤3：createdBy为空且assignedTo为多个用户
r($kanbanTest->getToAndCcListTest($card4)) && p() && e('0'); // 测试步骤4：createdBy和assignedTo都为空
r($kanbanTest->getToAndCcListTest($card5)) && p('0,1') && e('user6,user7'); // 测试步骤5：assignedTo包含逗号分隔符处理
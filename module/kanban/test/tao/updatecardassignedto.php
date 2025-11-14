#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::updateCardAssignedTo();
timeout=0
cid=16993

- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是1, 'admin, user1, user2', array  @admin,user1,user2

- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是2, 'admin, user1, user2', array  @admin,user1

- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是3, 'admin, user1, user2', array  @0
- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是4, '', array  @0
- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是5, 'admin', array  @admin
- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是6, 'admin, user1, user2, user3', array  @admin,user2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('kanbancard')->loadYaml('updatecardassignedto/kanbancard', false, 2)->gen(6);

global $tester;

$tester->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq('admin,user1,user2')->where('id')->eq(1)->exec();
$tester->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq('admin,user1,user2')->where('id')->eq(2)->exec();
$tester->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq('admin,user1,user2')->where('id')->eq(3)->exec();
$tester->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq('')->where('id')->eq(4)->exec();
$tester->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq('admin')->where('id')->eq(5)->exec();
$tester->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq('admin,user1,user2,user3')->where('id')->eq(6)->exec();

su('admin');

$kanbanTest = new kanbanTaoTest();

r($kanbanTest->updateCardAssignedToTest(1, 'admin,user1,user2', array('admin' => 'Admin', 'user1' => 'User1', 'user2' => 'User2'))) && p() && e('admin,user1,user2');
r($kanbanTest->updateCardAssignedToTest(2, 'admin,user1,user2', array('admin' => 'Admin', 'user1' => 'User1'))) && p() && e('admin,user1');
r($kanbanTest->updateCardAssignedToTest(3, 'admin,user1,user2', array('user3' => 'User3', 'user4' => 'User4'))) && p() && e('0');
r($kanbanTest->updateCardAssignedToTest(4, '', array('admin' => 'Admin', 'user1' => 'User1'))) && p() && e('0');
r($kanbanTest->updateCardAssignedToTest(5, 'admin', array('admin' => 'Admin', 'user1' => 'User1'))) && p() && e('admin');
r($kanbanTest->updateCardAssignedToTest(6, 'admin,user1,user2,user3', array('admin' => 'Admin', 'user2' => 'User2'))) && p() && e('admin,user2');
#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::updateCardAssignedTo();
timeout=0
cid=0

- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是1, 'admin, user1', $users 
 - 属性result @success
 - 属性afterAssignedTo @admin
- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是2, 'user2, invalid, user3', $users 
 - 属性result @success
 - 属性afterAssignedTo @user2
- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是3, 'admin', $users 
 - 属性result @success
 - 属性afterAssignedTo @admin
- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是4, 'invalid1, invalid2', $users 
 - 属性result @success
 - 属性afterAssignedTo @
- 执行kanbanTest模块的updateCardAssignedToTest方法，参数是5, 'user1, , admin, ', $users 
 - 属性result @success
 - 属性afterAssignedTo @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

zenData('kanbancard')->loadYaml('kanbancard_updatecardassignedto', false, 2)->gen(6);

$users = array(
    'admin' => 'Administrator', 
    'user1' => 'User One',
    'user2' => 'User Two', 
    'user3' => 'User Three'
);

su('admin');

$kanbanTest = new kanbanTest();

r($kanbanTest->updateCardAssignedToTest(1, 'admin,user1', $users)) && p('result,afterAssignedTo') && e('success,admin,user1');
r($kanbanTest->updateCardAssignedToTest(2, 'user2,invalid,user3', $users)) && p('result,afterAssignedTo') && e('success,user2,user3');
r($kanbanTest->updateCardAssignedToTest(3, 'admin', $users)) && p('result,afterAssignedTo') && e('success,admin');
r($kanbanTest->updateCardAssignedToTest(4, 'invalid1,invalid2', $users)) && p('result,afterAssignedTo') && e('success,');
r($kanbanTest->updateCardAssignedToTest(5, 'user1,,admin,', $users)) && p('result,afterAssignedTo') && e('success,user1,admin');
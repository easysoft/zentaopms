#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 todoModel::getToAndCcList();
timeout=0
cid=0

- 测试步骤1：有指派给和所属人 @dev1
- 测试步骤2：仅有所属人 @admin
- 测试步骤3：仅指派给 @dev1
- 测试步骤4：均为空返回false @0
- 测试步骤5：抄送人为空属性1 @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$todoTest = new todoModelTest();

$assignedTodo = new stdclass();
$assignedTodo->assignedTo = 'dev1';
$assignedTodo->account    = 'admin';

$accountTodo = new stdclass();
$accountTodo->assignedTo = '';
$accountTodo->account    = 'admin';

$assignedOnlyTodo = new stdclass();
$assignedOnlyTodo->assignedTo = 'dev1';
$assignedOnlyTodo->account    = '';

$emptyTodo = new stdclass();
$emptyTodo->assignedTo = '';
$emptyTodo->account    = '';

r($todoTest->getToAndCcListTest($assignedTodo))     && p('0') && e('dev1');  // 测试步骤1：有指派给和所属人
r($todoTest->getToAndCcListTest($accountTodo))      && p('0') && e('admin'); // 测试步骤2：仅有所属人
r($todoTest->getToAndCcListTest($assignedOnlyTodo)) && p('0') && e('dev1');  // 测试步骤3：仅指派给
r($todoTest->getToAndCcListTest($emptyTodo))        && p()    && e('0');     // 测试步骤4：均为空返回false
r($todoTest->getToAndCcListTest($assignedTodo))     && p('1') && e('~~');    // 测试步骤5：抄送人为空

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testrun')->gen(5);

su('admin');

/**

title=测试 testtaskModel->assignCase();
cid=19154

- 测试指派测试单ID 为 1 的用例给 admin属性assignedTo @admin
- 测试指派测试单ID 为 2 的用例给 admin属性assignedTo @admin
- 测试指派测试单ID 为 3 的用例给 admin属性assignedTo @admin
- 测试指派测试单ID 为 4 的用例给 admin属性assignedTo @admin
- 测试指派测试单ID 为 5 的用例给 admin属性assignedTo @admin
- 测试指派测试单ID 不存在的 10001 的用例给 admin属性assignedTo @0
- 测试指派测试单ID 不存在的 0 的用例给 admin属性assignedTo @0
- 测试指派测试单ID 为 1 的用例给 user2属性assignedTo @user2
- 测试指派测试单ID 为 2 的用例给 user2属性assignedTo @user2
- 测试指派测试单ID 为 3 的用例给 user2属性assignedTo @user2
- 测试指派测试单ID 为 4 的用例给 user2属性assignedTo @user2
- 测试指派测试单ID 为 5 的用例给 user2属性assignedTo @user2
- 测试指派测试单ID 不存在的 10001 的用例给 user2属性assignedTo @0
- 测试指派测试单ID 不存在的 0 的用例给 user2属性assignedTo @0

*/

$uid = uniqid();

$runIdList      = array(1, 2, 3, 4, 5, 10001, 0);
$assignedToList = array('admin', 'user2');

$testtask = new testtaskModelTest();

r($testtask->assignCaseTest($runIdList[0], $assignedToList[0])) && p('assignedTo') && e('admin'); // 测试指派测试单ID 为 1 的用例给 admin
r($testtask->assignCaseTest($runIdList[1], $assignedToList[0])) && p('assignedTo') && e('admin'); // 测试指派测试单ID 为 2 的用例给 admin
r($testtask->assignCaseTest($runIdList[2], $assignedToList[0])) && p('assignedTo') && e('admin'); // 测试指派测试单ID 为 3 的用例给 admin
r($testtask->assignCaseTest($runIdList[3], $assignedToList[0])) && p('assignedTo') && e('admin'); // 测试指派测试单ID 为 4 的用例给 admin
r($testtask->assignCaseTest($runIdList[4], $assignedToList[0])) && p('assignedTo') && e('admin'); // 测试指派测试单ID 为 5 的用例给 admin
r($testtask->assignCaseTest($runIdList[5], $assignedToList[0])) && p('assignedTo') && e('0');     // 测试指派测试单ID 不存在的 10001 的用例给 admin
r($testtask->assignCaseTest($runIdList[6], $assignedToList[0])) && p('assignedTo') && e('0');     // 测试指派测试单ID 不存在的 0 的用例给 admin

r($testtask->assignCaseTest($runIdList[0], $assignedToList[1])) && p('assignedTo') && e('user2'); // 测试指派测试单ID 为 1 的用例给 user2
r($testtask->assignCaseTest($runIdList[1], $assignedToList[1])) && p('assignedTo') && e('user2'); // 测试指派测试单ID 为 2 的用例给 user2
r($testtask->assignCaseTest($runIdList[2], $assignedToList[1])) && p('assignedTo') && e('user2'); // 测试指派测试单ID 为 3 的用例给 user2
r($testtask->assignCaseTest($runIdList[3], $assignedToList[1])) && p('assignedTo') && e('user2'); // 测试指派测试单ID 为 4 的用例给 user2
r($testtask->assignCaseTest($runIdList[4], $assignedToList[1])) && p('assignedTo') && e('user2'); // 测试指派测试单ID 为 5 的用例给 user2
r($testtask->assignCaseTest($runIdList[5], $assignedToList[1])) && p('assignedTo') && e('0');     // 测试指派测试单ID 不存在的 10001 的用例给 user2
r($testtask->assignCaseTest($runIdList[6], $assignedToList[1])) && p('assignedTo') && e('0');     // 测试指派测试单ID 不存在的 0 的用例给 user2

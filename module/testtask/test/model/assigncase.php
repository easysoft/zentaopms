#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

zenData('testrun')->gen(5);

su('admin');

/**

title=测试 testtaskModel->assignCase();
cid=1
pid=1

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

#!/usr/bin/env php
<?php

/**

title=测试 userModel::getManageListGroupByType();
timeout=0
cid=19619

- 测试用户admin的programs权限为all，isAdmin为1第programs条的isAdmin属性 @1
- 测试用户admin的projects权限为all，isAdmin为1第projects条的isAdmin属性 @1
- 测试用户admin返回5个权限类型 @5
- 测试用户user1返回5个权限类型 @5
- 测试用户user2返回5个权限类型 @5
- 测试用户noauth返回空数组 @0
- 测试空字符串参数返回空数组 @0
- 测试不存在用户返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

zendata('projectadmin')->loadYaml('projectadmin_getmanagelistgroupbytype', false, 2)->gen(4);

su('admin');

$userTest = new userTest();

r($userTest->getManageListGroupByTypeTest('admin')) && p('programs:isAdmin') && e('1'); // 测试用户admin的programs权限为all，isAdmin为1
r($userTest->getManageListGroupByTypeTest('admin')) && p('projects:isAdmin') && e('1'); // 测试用户admin的projects权限为all，isAdmin为1
r(count($userTest->getManageListGroupByTypeTest('admin'))) && p() && e('5'); // 测试用户admin返回5个权限类型
r(count($userTest->getManageListGroupByTypeTest('user1'))) && p() && e('5'); // 测试用户user1返回5个权限类型
r(count($userTest->getManageListGroupByTypeTest('user2'))) && p() && e('5'); // 测试用户user2返回5个权限类型
r(count($userTest->getManageListGroupByTypeTest('noauth'))) && p() && e('0'); // 测试用户noauth返回空数组
r(count($userTest->getManageListGroupByTypeTest(''))) && p() && e('0'); // 测试空字符串参数返回空数组
r(count($userTest->getManageListGroupByTypeTest('notexist'))) && p() && e('0'); // 测试不存在用户返回空数组
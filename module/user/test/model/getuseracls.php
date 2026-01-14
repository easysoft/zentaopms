#!/usr/bin/env php
<?php

/**

title=测试 userModel::getUserAcls();
timeout=0
cid=19635

- 步骤1：测试guest用户权限获取 @1
- 步骤2：测试admin用户权限获取 @1
- 步骤3：测试有权限组用户的权限获取 @1
- 步骤4：测试不存在用户的权限获取 @1
- 步骤5：测试返回结构包含programs字段 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('group')->loadYaml('group_getuseracls', false, 2)->gen(10);
zendata('usergroup')->loadYaml('usergroup_getuseracls', false, 2)->gen(10);

su('admin');

$userTest = new userModelTest();

r(is_array($userTest->getUserAclsTest('guest'))) && p() && e('1'); // 步骤1：测试guest用户权限获取
r(is_array($userTest->getUserAclsTest('admin'))) && p() && e('1'); // 步骤2：测试admin用户权限获取
r(is_array($userTest->getUserAclsTest('dev1'))) && p() && e('1'); // 步骤3：测试有权限组用户的权限获取
r(is_array($userTest->getUserAclsTest('nonexistent'))) && p() && e('1'); // 步骤4：测试不存在用户的权限获取
r(array_key_exists('programs', $userTest->getUserAclsTest('admin'))) && p() && e('1'); // 步骤5：测试返回结构包含programs字段
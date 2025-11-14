#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getPrivs();
timeout=0
cid=16708

- 步骤1：测试正常分组ID获取存在的权限属性module1 @method1
- 步骤2：测试正常分组ID获取另一个存在的权限属性module6 @method6
- 步骤3：测试正常分组ID获取不存在的模块权限属性module2 @~~
- 步骤4：测试不存在的分组ID @0
- 步骤5：测试分组ID为0的边界情况 @0
- 步骤6：测试有多个权限的分组，验证数据结构完整性
 - 属性module5 @method5
 - 属性module7 @method7
 - 属性module8 @method8
- 步骤7：测试负数分组ID的边界情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

$groupTable = zenData('group');
$groupTable->id->range('1-5');
$groupTable->name->range('admin,user,guest,manager,developer');
$groupTable->role->range('admin{1},limited{4}');
$groupTable->gen(5);

$privTable = zenData('grouppriv');
$privTable->group->range('1{2},2{2},3{3},4{2},5{1}');
$privTable->module->range('module1,module6,module3,module4,module5,module7,module8,module9,module10');
$privTable->method->range('method1,method6,method3,method4,method5,method7,method8,method9,method10');
$privTable->gen(10);

$group = new groupTest();

r($group->getPrivsTest(1)) && p('module1') && e('method1');         // 步骤1：测试正常分组ID获取存在的权限
r($group->getPrivsTest(1)) && p('module6') && e('method6');         // 步骤2：测试正常分组ID获取另一个存在的权限
r($group->getPrivsTest(1)) && p('module2') && e('~~');              // 步骤3：测试正常分组ID获取不存在的模块权限
r($group->getPrivsTest(999)) && p() && e('0');                      // 步骤4：测试不存在的分组ID
r($group->getPrivsTest(0)) && p() && e('0');                        // 步骤5：测试分组ID为0的边界情况
r($group->getPrivsTest(3)) && p('module5,module7,module8') && e('method5,method7,method8'); // 步骤6：测试有多个权限的分组，验证数据结构完整性
r($group->getPrivsTest(-1)) && p() && e('0');                       // 步骤7：测试负数分组ID的边界情况
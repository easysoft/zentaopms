#!/usr/bin/env php
<?php

/**

title=测试 deptModel::getChildDepts();
timeout=0
cid=15969

- 测试步骤1：获取所有部门，验证总数量 @50
- 测试步骤2：获取ID为1的部门，验证部门名称第1条的name属性 @产品部1
- 测试步骤3：获取不存在部门的子部门，返回所有部门 @50
- 测试步骤4：验证部门层级信息第3条的parent属性 @0
- 测试步骤5：验证特定父部门下子部门数量 @7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('dept')->loadYaml('dept')->gen(50)->fixPath();

su('admin');

$deptTest = new deptModelTest();

r($deptTest->getChildDeptsTest(0, 'count')) && p() && e('50');            // 测试步骤1：获取所有部门，验证总数量
r($deptTest->getChildDeptsTest(1)) && p('1:name') && e('产品部1');        // 测试步骤2：获取ID为1的部门，验证部门名称
r($deptTest->getChildDeptsTest(999, 'count')) && p() && e('50');         // 测试步骤3：获取不存在部门的子部门，返回所有部门
r($deptTest->getChildDeptsTest(3)) && p('3:parent') && e('0');           // 测试步骤4：验证部门层级信息
r($deptTest->getChildDeptsTest(2, 'count')) && p() && e('7');            // 测试步骤5：验证特定父部门下子部门数量
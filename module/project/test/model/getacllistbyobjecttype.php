#!/usr/bin/env php
<?php

/**

title=测试 projectModel::getAclListByObjectType();
timeout=0
cid=17813

- 步骤1：查询project类型记录数量 @5
- 步骤2：查询product类型记录数量 @5
- 步骤3：查询不存在类型 @0
- 步骤4：查询多个类型 @10
- 步骤5：查询空字符串 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('acl')->loadYaml('acl_getacllistbyobjecttype', false, 2)->gen(20);
su('admin');

$projectTester = new ProjectTest();

r(count($projectTester->getAclListByObjectTypeTest('project')))         && p() && e('5'); // 步骤1：查询project类型记录数量
r(count($projectTester->getAclListByObjectTypeTest('product')))         && p() && e('5'); // 步骤2：查询product类型记录数量
r(count($projectTester->getAclListByObjectTypeTest('nonexist')))        && p() && e('0'); // 步骤3：查询不存在类型
r(count($projectTester->getAclListByObjectTypeTest('project,product'))) && p() && e('10'); // 步骤4：查询多个类型
r(count($projectTester->getAclListByObjectTypeTest('')))                && p() && e('0'); // 步骤5：查询空字符串
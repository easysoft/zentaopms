#!/usr/bin/env php
<?php

/**

title=测试 projectModel::getListByAcl();
timeout=0
cid=0

- 步骤1：查询open权限的项目 @5
- 步骤2：查询private权限的项目 @4
- 步骤3：查询不存在的权限类型 @0
- 步骤4：查询open权限并过滤指定ID列表 @3
- 步骤5：查询whitelist权限的项目 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备
zenData('project')->loadYaml('project_getlistbyacl', false, 2)->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new Project();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($projectTest->getListByAclTest('open')) && p() && e('5'); // 步骤1：查询open权限的项目
r($projectTest->getListByAclTest('private')) && p() && e('4'); // 步骤2：查询private权限的项目
r($projectTest->getListByAclTest('nonexist')) && p() && e('0'); // 步骤3：查询不存在的权限类型
r($projectTest->getListByAclTest('open', array(1, 2, 3))) && p() && e('3'); // 步骤4：查询open权限并过滤指定ID列表
r($projectTest->getListByAclTest('whitelist')) && p() && e('3'); // 步骤5：查询whitelist权限的项目
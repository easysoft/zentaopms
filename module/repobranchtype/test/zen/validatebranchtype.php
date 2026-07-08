#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeZen::validateBranchType 方法();
timeout=0
cid=0

- 测试步骤1：验证合法的分支类型数据 @1
- 测试步骤2：验证key格式不正确（以数字开头） @0
- 测试步骤3：验证key格式不正确（包含特殊字符） @0
- 测试步骤4：验证prefix格式正确 @1
- 测试步骤5：验证prefix格式不正确（包含多个斜杠） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);
zenData('ops_branch_type')->gen(5);

su('admin');

$zenTest = new repobranchtypeZenTest();

// 测试步骤1：验证合法的分支类型数据
$validType = new stdclass();
$validType->key      = 'feature';
$validType->prefixes = array('feature/', 'feat/');
r($zenTest->validateBranchTypeTest($validType)) && p() && e('1');

// 测试步骤2：验证key格式不正确（以数字开头）
dao::$errors = array();
$invalidKey = new stdclass();
$invalidKey->key      = '123feature';
$invalidKey->prefixes = array('feature/');
r($zenTest->validateBranchTypeTest($invalidKey)) && p() && e('0');

// 测试步骤3：验证key格式不正确（包含特殊字符）
dao::$errors = array();
$invalidKey2 = new stdclass();
$invalidKey2->key      = 'feature@test';
$invalidKey2->prefixes = array('feature/');
r($zenTest->validateBranchTypeTest($invalidKey2)) && p() && e('0');

// 测试步骤4：验证prefix格式正确
dao::$errors = array();
$validPrefix = new stdclass();
$validPrefix->key      = 'develop';
$validPrefix->prefixes = array('dev/', 'develop/');
r($zenTest->validateBranchTypeTest($validPrefix)) && p() && e('1');

// 测试步骤5：验证prefix格式不正确（包含多个斜杠）
dao::$errors = array();
$invalidPrefix = new stdclass();
$invalidPrefix->key      = 'bugfix';
$invalidPrefix->prefixes = array('bug/fix/test');
r($zenTest->validateBranchTypeTest($invalidPrefix)) && p() && e('0');

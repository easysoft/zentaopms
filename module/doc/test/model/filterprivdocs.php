#!/usr/bin/env php
<?php

/**

title=测试 docModel::filterPrivDocs();
timeout=0
cid=16067

- 执行$result1 @0
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1
- 执行$result6 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备  
$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->gen(3);

$usergroup = zenData('usergroup');
$usergroup->account->range('admin,user1,user2');
$usergroup->group->range('1,2,2');
$usergroup->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docModelTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：空数组边界测试
$emptyDocs = array();
$result1 = $docTest->filterPrivDocsTest($emptyDocs, 'mine');
r(count($result1)) && p() && e('0'); 

// 步骤2：开放权限文档测试
$openDoc = new stdClass();
$openDoc->id = 1;
$openDoc->title = '开放文档';
$openDoc->acl = 'open';
$openDoc->addedBy = 'admin';
$openDoc->users = '';
$openDoc->readUsers = '';
$openDoc->groups = '';
$openDoc->readGroups = '';
$openDoc->path = ',';
$result2 = $docTest->filterPrivDocsTest(array($openDoc), 'mine');
r(count($result2)) && p() && e('1');

// 步骤3：私有权限文档测试（作者访问）
$privateDoc = new stdClass();
$privateDoc->id = 2;
$privateDoc->title = '私有文档';
$privateDoc->acl = 'private';
$privateDoc->addedBy = 'admin'; // 当前用户是作者
$privateDoc->users = '';
$privateDoc->readUsers = '';
$privateDoc->groups = '';
$privateDoc->readGroups = '';
$privateDoc->path = ',';
$result3 = $docTest->filterPrivDocsTest(array($privateDoc), 'mine');
r(count($result3)) && p() && e('1');

// 步骤4：自定义权限文档测试（用户在可读用户列表中）
$customDoc = new stdClass();
$customDoc->id = 3;
$customDoc->title = '自定义文档';
$customDoc->acl = 'custom';
$customDoc->addedBy = 'user1';
$customDoc->users = '';
$customDoc->readUsers = ',admin,user1,';
$customDoc->groups = '';
$customDoc->readGroups = '';
$customDoc->path = ',';
$result4 = $docTest->filterPrivDocsTest(array($customDoc), 'mine');
r(count($result4)) && p() && e('1');

// 步骤5：模板空间类型测试
$templateDoc = new stdClass();
$templateDoc->id = 4;
$templateDoc->title = '模板文档';
$templateDoc->acl = 'open';
$templateDoc->addedBy = 'admin';
$templateDoc->users = '';
$templateDoc->readUsers = '';
$templateDoc->groups = '';
$templateDoc->readGroups = '';
$templateDoc->path = ',';
$result5 = $docTest->filterPrivDocsTest(array($templateDoc), 'template');
r(count($result5)) && p() && e('1');

// 步骤6：混合权限文档测试
$mixedDocs = array($openDoc, $privateDoc, $customDoc);
$result6 = $docTest->filterPrivDocsTest($mixedDocs, 'mine');
r(count($result6)) && p() && e('3');
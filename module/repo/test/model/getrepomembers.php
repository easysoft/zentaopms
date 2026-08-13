#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getRepoMembers();
timeout=0
cid=0

- 步骤1：成员列表为空时返回空数组 @0
- 步骤2：单个已存在成员返回真实姓名 @管理员
- 步骤3：多个已存在成员都能映射真实姓名 @管理员,用户一
- 步骤4：夹杂不存在成员时只返回存在成员 @用户一
- 步骤5：全部成员都不存在时返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户一,用户二');
$user->gen(3);

su('admin');

global $lang;
if(!isset($lang->codescan)) $lang->codescan = new stdclass();
if(!isset($lang->codescan->exec)) $lang->codescan->exec = 'exec';
if(!isset($lang->codescan->issue)) $lang->codescan->issue = 'issue';

$repoTest = new repoModelTest();

$emptyRepo   = (object)array('members' => array());
$adminRepo   = (object)array('members' => array('admin' => 'admin'));
$multiRepo   = (object)array('members' => array('admin' => 'admin', 'user1' => 'user1'));
$mixedRepo   = (object)array('members' => array('user1' => 'user1', 'ghost' => 'ghost'));
$missingRepo = (object)array('members' => array('ghost' => 'ghost'));

r($repoTest->getRepoMembersCountTest($emptyRepo)) && p() && e('0');
r($repoTest->getRepoMembersTest($adminRepo))          && p('admin')       && e('管理员');
r($repoTest->getRepoMembersTest($multiRepo))          && p('admin,user1') && e('管理员,用户一');
r($repoTest->getRepoMembersTest($mixedRepo))          && p('user1')       && e('用户一');
r($repoTest->getRepoMembersCountTest($missingRepo)) && p() && e('0');

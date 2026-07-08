#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->insertMembers();
timeout=0
cid=18125

- 测试 insertMembers 返回成功 @success
- 测试 insertMembers 传入重复成员返回成功 @success
*/

zenData('repo')->loadYaml('repo')->gen(1);

$repo = new repoModelTest();

$members = array('dev1', 'dev2');
$duplicateMembers = array('dev1', 'dev1', 'dev2');

r($repo->insertMembersTest($members)) && p() && e('success');
r($repo->insertMembersTest($duplicateMembers)) && p() && e('success');

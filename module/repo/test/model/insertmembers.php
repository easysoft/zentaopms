#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->insertMembers();
timeout=0
cid=18125

- 测试 insertMembers 传入指定 repoID 返回成功 @success
- 测试 insertMembers 传入指定 repoID 和重复成员返回成功 @success
- 测试 insertMembers 传入指定 repoID 和单成员返回成功 @success
- 测试 insertMembers 传入指定 repoID 和三成员返回成功 @success
- 测试 insertMembers 传入指定 repoID 和另一组成员返回成功 @success
*/

$repo = new repoModelTest();

r($repo->insertMembersTest(1, array('dev1', 'dev2'))) && p() && e('success');
r($repo->insertMembersTest(2, array('dev1', 'dev1', 'dev2'))) && p() && e('success');
r($repo->insertMembersTest(3, array('dev3'))) && p() && e('success');
r($repo->insertMembersTest(4, array('dev4', 'dev5', 'dev6'))) && p() && e('success');
r($repo->insertMembersTest(5, array('qa1', 'qa2'))) && p() && e('success');

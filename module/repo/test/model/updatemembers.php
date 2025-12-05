#!/usr/bin/env php
<?php

/**

title=测试 groupModel->updateUser();
timeout=0
cid=0

- 更新分组成员，检查已有用户属性user1 @user1
- 更新分组成员，检查已删除用户属性user6 @~~
- 更新分组成员，检查新增用户属性user10 @user10
- 更新分组成员，检查已有用户属性user1 @user1
- 更新分组成员，检查新增用户属性user10 @user10
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

zenData('user')->gen(100);
zenData('repo')->gen(5);
zenData('ops_repouser')->gen(10);

$repo = new repoTest();

$members = array('user1' => 'user1', 'user10' => 'user10');

r($repo->updateMembersTest(1, $members)) && p('user1')  && e('user1');  // 更新分组成员，检查已有用户
r($repo->updateMembersTest(1, $members)) && p('user6')  && e('~~');     // 更新分组成员，检查已删除用户
r($repo->updateMembersTest(1, $members)) && p('user10') && e('user10'); // 更新分组成员，检查新增用户
r($repo->updateMembersTest(2, $members)) && p('user1')  && e('user1');  // 更新分组成员，检查已有用户
r($repo->updateMembersTest(2, $members)) && p('user10') && e('user10'); // 更新分组成员，检查新增用户

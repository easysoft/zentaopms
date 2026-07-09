#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateMembers();
timeout=0
cid=0

- 更新分组成员，检查已有用户属性user1 @user1
- 更新分组成员，检查已删除用户属性user6 @~~
- 更新分组成员，检查新增用户属性user10 @user10
- 更新分组成员，检查已有用户属性user1 @user1
- 更新分组成员，检查新增用户属性user10 @user10
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

global $tester;
zenData('user')->gen(100);
$tester->dao->delete()->from(TABLE_DEVOPSREPOUSER)->where('repo')->in('1,2')->exec();

foreach(array(1, 2) as $repoID)
{
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'user1'))->exec();
    $tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => $repoID, 'account' => 'user6'))->exec();
}

$repo = new repoTaoTest();

$members = array('user1' => 'user1', 'user10' => 'user10');

r($repo->updateMembersTest(1, $members)) && p('user1')  && e('user1');  // repo1 保留已有用户
r($repo->updateMembersTest(1, $members)) && p('user6')  && e('~~');     // repo1 删除未保留用户
r($repo->updateMembersTest(1, $members)) && p('user10') && e('user10'); // repo1 新增成员
r($repo->updateMembersTest(2, $members)) && p('user1')  && e('user1');  // repo2 保留已有用户
r($repo->updateMembersTest(2, $members)) && p('user10') && e('user10'); // repo2 新增成员

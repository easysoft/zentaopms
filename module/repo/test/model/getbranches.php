#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getBranches();
timeout=0
cid=1

- 获取gitlab类型版本库1的分支
 - 属性master @master
 - 属性branch1 @branch1
- 获取gitlab类型版本库1的分支加label
 - 属性master @Branch::master
 - 属性branch1 @Branch::branch1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$repoIds = array(1);

$repo = new repoTest();

r($repo->getBranchesTest($repoIds[0]))       && p('master,branch1') && e('master,branch1'); // 获取gitlab类型版本库1的分支
r($repo->getBranchesTest($repoIds[0], true)) && p('master,branch1') && e('Branch::master,Branch::branch1'); // 获取gitlab类型版本库1的分支加label
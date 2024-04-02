#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveBranchRelation();
timeout=0
cid=8

- 保存任务和分支的关联关系
 - 属性AID @2
 - 属性BID @1
 - 属性extra @master
- 保存需求和分支的关联关系
 - 属性AID @3
 - 属性BID @2
 - 属性extra @story
- 保存Bug和分支的关联关系
 - 属性AID @4
 - 属性BID @3
 - 属性extra @bug

*/

$repo = new repoTest();

$repoID     = 1;
$branch     = 'master';
$objectID   = 2;
$objectType = 'task';
r($repo->saveBranchRelationTest($repoID, $branch, $objectID, $objectType)) && p('AID,BID,extra') && e('2,1,master'); //保存任务和分支的关联关系

$repoID     = 2;
$branch     = 'story';
$objectID   = 3;
$objectType = 'story';
r($repo->saveBranchRelationTest($repoID, $branch, $objectID, $objectType)) && p('AID,BID,extra') && e('3,2,story'); //保存需求和分支的关联关系

$repoID     = 3;
$branch     = 'bug';
$objectID   = 4;
$objectType = 'bug';
r($repo->saveBranchRelationTest($repoID, $branch, $objectID, $objectType)) && p('AID,BID,extra') && e('4,3,bug'); //保存Bug和分支的关联关系
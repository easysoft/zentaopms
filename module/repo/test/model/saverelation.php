#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->saveRelation();
timeout=0
cid=18100

- 保存任务和分支的关联关系
 - 属性AID @2
 - 属性BID @1
 - 属性BType @master
- 保存需求和分支的关联关系
 - 属性AID @3
 - 属性BID @2
 - 属性BType @story
- 保存Bug和分支的关联关系
 - 属性AID @4
 - 属性BID @3
 - 属性BType @bug

*/

$repo = new repoTest();

$repoID     = 1;
$branch     = 'master';
$objectID   = 2;
$objectType = 'task';
r($repo->saveRelationTest($repoID, $branch, $objectID, $objectType)) && p('AID,BID,BType') && e('2,1,master'); //保存任务和分支的关联关系

$repoID     = 2;
$branch     = 'story';
$objectID   = 3;
$objectType = 'story';
r($repo->saveRelationTest($repoID, $branch, $objectID, $objectType)) && p('AID,BID,BType') && e('3,2,story'); //保存需求和分支的关联关系

$repoID     = 3;
$branch     = 'bug';
$objectID   = 4;
$objectType = 'bug';
r($repo->saveRelationTest($repoID, $branch, $objectID, $objectType)) && p('AID,BID,BType') && e('4,3,bug'); //保存Bug和分支的关联关系
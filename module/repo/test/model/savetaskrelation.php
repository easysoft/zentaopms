#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveTaskRelation();
timeout=0
cid=8

- 保存任务和分支的关联关系
 - 属性AID @2
 - 属性BID @1
 - 属性extra @master

*/

$repo = new repoTest();

$repoID = 1;
$taskID = 2;
$branch = 'master';

r($repo->saveTaskRelationTest($repoID, $taskID, $branch)) && p('AID,BID,extra') && e('2,1,master'); //保存任务和分支的关联关系

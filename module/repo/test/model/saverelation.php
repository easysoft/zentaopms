#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
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
- 保存另一个任务关联到同一repo >> 1
- 保存关联到不同repo >> 1

*/

$repo = new repoModelTest();

r($repo->saveRelationTest(1, 'master', 2, 'task'))   && p('AID,BID,BType') && e('2,1,master');
r($repo->saveRelationTest(2, 'story',  3, 'story'))  && p('AID,BID,BType') && e('3,2,story');
r($repo->saveRelationTest(3, 'bug',    4, 'bug'))    && p('AID,BID,BType') && e('4,3,bug');
r($repo->saveRelationTest(1, 'develop', 5, 'task') && $repo->saveRelationTest(1, 'develop', 5, 'task')->AID == 5) && p() && e('1');
r($repo->saveRelationTest(4, 'release', 6, 'task') && $repo->saveRelationTest(4, 'release', 6, 'task')->BID == 4) && p() && e('1');

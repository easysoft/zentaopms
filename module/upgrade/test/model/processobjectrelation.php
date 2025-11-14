#!/usr/bin/env php
<?php

/**

title=upgradeModel->processLinkStories();
timeout=0
cid=19545

- 获取bug的关联关系
 - 第0条的AType属性 @story
 - 第0条的AID属性 @2
 - 第0条的relation属性 @generated
 - 第0条的BType属性 @bug
 - 第0条的BID属性 @1
 - 第1条的AType属性 @task
 - 第1条的AID属性 @1
 - 第1条的relation属性 @generated
 - 第1条的BType属性 @bug
 - 第1条的BID属性 @1
- 获取testcase的关联关系
 - 第0条的AType属性 @story
 - 第0条的AID属性 @3
 - 第0条的relation属性 @generated
 - 第0条的BType属性 @testcase
 - 第0条的BID属性 @1
- 获取设计的关联关系
 - 第0条的AType属性 @story
 - 第0条的AID属性 @4
 - 第0条的relation属性 @generated
 - 第0条的BType属性 @design
 - 第0条的BID属性 @1
- 获取发布的关联关系
 - 第0条的AType属性 @story
 - 第0条的AID属性 @5
 - 第0条的relation属性 @interrated
 - 第0条的BType属性 @release
 - 第0条的BID属性 @1
- 获取版本的关联关系
 - 第0条的AType属性 @story
 - 第0条的AID属性 @6
 - 第0条的relation属性 @interrated
 - 第0条的BType属性 @build
 - 第0条的BID属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

su('admin');

zenData('story')->gen(10);
zenData('task')->gen(10);

$bug = zenData('bug');
$bug->task->range('1');
$bug->story->range('2');
$bug->gen(1);

$case = zenData('case');
$case->story->range('3');
$case->gen(1);

$design = zenData('design');
$design->story->range('4');
$design->gen(1);

$release = zenData('release');
$release->stories->range('5');
$release->gen(1);

$build = zenData('build');
$build->stories->range('6');
$build->gen(1);

zenData('relation')->gen(0);

$upgrade = new upgradeTest();
zenData('relation')->gen(0);
r($upgrade->processObjectRelationTest('bug', 1)) && p('0:AType,AID,relation,BType,BID;1:AType,AID,relation,BType,BID') && e('story,2,generated,bug,1;task,1,generated,bug,1'); // 获取bug的关联关系
zenData('relation')->gen(0);
r($upgrade->processObjectRelationTest('testcase', 1)) && p('0:AType,AID,relation,BType,BID') && e('story,3,generated,testcase,1'); // 获取testcase的关联关系
zenData('relation')->gen(0);
r($upgrade->processObjectRelationTest('design', 1)) && p('0:AType,AID,relation,BType,BID') && e('story,4,generated,design,1'); // 获取设计的关联关系
zenData('relation')->gen(0);
r($upgrade->processObjectRelationTest('release', 1)) && p('0:AType,AID,relation,BType,BID') && e('story,5,interrated,release,1'); // 获取发布的关联关系
zenData('relation')->gen(0);
r($upgrade->processObjectRelationTest('build', 1)) && p('0:AType,AID,relation,BType,BID') && e('story,6,interrated,build,1'); // 获取版本的关联关系

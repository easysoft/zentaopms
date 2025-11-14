#!/usr/bin/env php
<?php

/**

title=测试 designModel::getLinkedCommits();
timeout=0
cid=15991

- 步骤1：正常情况查询设计提交关联数据 @2
- 步骤2：查询不存在的仓库ID @0
- 步骤3：查询不存在的修订号 @0
- 步骤4：查询空的修订号数组 @0
- 步骤5：查询多个修订号 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/design.unittest.class.php';

zenData('repohistory')->gen(0);
zenData('relation')->gen(0);
zenData('design')->gen(0);

$table = zenData('repohistory');
$table->id->range('1-5');
$table->repo->range('1{5}');
$table->revision->range('abc123,def456,ghi789,jkl012,mno345');
$table->commit->range('1-5');
$table->comment->range('Initial commit,Bug fix,Feature update,Code refactor,Test update');
$table->committer->range('admin,user1,user2,dev1,dev2');
$table->gen(5);

$table = zenData('relation');
$table->id->range('1-5');
$table->project->range('0{5}');
$table->product->range('0{5}');
$table->execution->range('0{5}');
$table->AType->range('design{5}');
$table->AID->range('1,2,3,4,5');
$table->AVersion->range('{5}');
$table->relation->range('completedin{5}');
$table->BType->range('commit{5}');
$table->BID->range('1,2,3,4,5');
$table->BVersion->range('{5}');
$table->extra->range('{5}');
$table->gen(5);

$table = zenData('design');
$table->id->range('1-5');
$table->project->range('11{5}');
$table->product->range('1{5}');
$table->name->range('Design1,Design2,Design3,Design4,Design5');
$table->status->range('active{5}');
$table->createdBy->range('admin{5}');
$table->deleted->range('0{5}');
$table->story->range('0{5}');
$table->gen(5);

su('admin');

$designTest = new designTest();

r($designTest->getLinkedCommitsTest(1, array('abc123', 'def456'))) && p() && e('2'); // 步骤1：正常情况查询设计提交关联数据
r($designTest->getLinkedCommitsTest(999, array('abc123', 'def456'))) && p() && e('0'); // 步骤2：查询不存在的仓库ID
r($designTest->getLinkedCommitsTest(1, array('nonexist'))) && p() && e('0'); // 步骤3：查询不存在的修订号
r($designTest->getLinkedCommitsTest(1, array())) && p() && e('0'); // 步骤4：查询空的修订号数组
r($designTest->getLinkedCommitsTest(1, array('abc123', 'def456', 'ghi789'))) && p() && e('3'); // 步骤5：查询多个修订号
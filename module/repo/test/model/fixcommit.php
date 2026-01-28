#!/usr/bin/env php
<?php

/**

title=测试 repoModel::fixCommit();
timeout=0
cid=18045

- 测试步骤1：正常情况下修复repo3的第一条记录第3条的commit属性 @1
- 测试步骤2：空repo历史记录情况 @0
- 测试步骤3：不存在的repoID测试 @0
- 测试步骤4：单条历史记录情况第8条的commit属性 @1
- 测试步骤5：两条历史记录按时间排序验证第一条第1条的commit属性 @1
- 测试步骤6：验证repo5的第一条记录修复结果第9条的commit属性 @1
- 测试步骤7：验证repo1的第二条记录commit序号第2条的commit属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repoTable = zenData('repo');
$repoTable->id->range('1-5');
$repoTable->product->range('1');
$repoTable->name->range('repo1,repo2,repo3,repo4,repo5');
$repoTable->path->range('/tmp/repo{1-5}');
$repoTable->SCM->range('Git');
$repoTable->deleted->range('0');
$repoTable->gen(5);

$historyTable = zenData('repohistory');
$historyTable->id->range('1-10');
$historyTable->repo->range('1{2},3{5},4{1},5{2}');
$historyTable->revision->range('a1b2c3,d4e5f6,j1k2l3,m4n5o6,p7q8r9,s1t2u3,v4w5x6,y7z8a9,b1c2d3,final');
$historyTable->commit->range('100,200,300,400,500,600,700,800,900,1000');
$historyTable->comment->range('Initial commit,Fix bug,Update docs,Refactor code,Fix test,Add test,Update readme,Fix style,Final commit,Last commit');
$historyTable->committer->range('user1,user2,admin,dev1,dev2,tester1,tester2,pm1,admin,dev3');
$historyTable->time->range('`2022-01-01 10:00:00`,`2022-01-01 11:00:00`,`2022-01-01 13:00:00`,`2022-01-01 14:00:00`,`2022-01-01 15:00:00`,`2022-01-01 16:00:00`,`2022-01-01 17:00:00`,`2022-01-01 18:00:00`,`2022-01-01 19:00:00`,`2022-01-01 20:00:00`');
$historyTable->gen(10);

su('admin');

$repoTest = new repoModelTest();

r($repoTest->fixCommitTest(3)) && p('3:commit') && e('1'); // 测试步骤1：正常情况下修复repo3的第一条记录
r($repoTest->fixCommitTest(2)) && p() && e('0'); // 测试步骤2：空repo历史记录情况
r($repoTest->fixCommitTest(999)) && p() && e('0'); // 测试步骤3：不存在的repoID测试
r($repoTest->fixCommitTest(4)) && p('8:commit') && e('1'); // 测试步骤4：单条历史记录情况
r($repoTest->fixCommitTest(1)) && p('1:commit') && e('1'); // 测试步骤5：两条历史记录按时间排序验证第一条
r($repoTest->fixCommitTest(5)) && p('9:commit') && e('1'); // 测试步骤6：验证repo5的第一条记录修复结果
r($repoTest->fixCommitTest(1)) && p('2:commit') && e('2'); // 测试步骤7：验证repo1的第二条记录commit序号
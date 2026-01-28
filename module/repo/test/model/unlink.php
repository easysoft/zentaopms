#!/usr/bin/env php
<?php

/**

title=测试 repoModel::unlink();
timeout=0
cid=18107

- 执行repoTest模块的unlinkTest方法，参数是1, 'rev001', 'story', 1  @success
- 执行repoTest模块的unlinkTest方法，参数是1, 'rev002', 'bug', 2  @success
- 执行repoTest模块的unlinkTest方法，参数是2, 'rev003', 'task', 3  @success
- 执行repoTest模块的unlinkTest方法，参数是1, 'nonexistent', 'story', 1  @not_found
- 执行repoTest模块的unlinkTest方法，参数是1, 'rev001', 'invalidtype', 1  @no_relation

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repoTable = zenData('repo');
$repoTable->id->range('1-3');
$repoTable->product->range('1');
$repoTable->name->range('测试代码库{1-3}');
$repoTable->SCM->range('Git');
$repoTable->deleted->range('0');
$repoTable->gen(3);

$repohistoryTable = zenData('repohistory');
$repohistoryTable->id->range('1-5');
$repohistoryTable->repo->range('1{2},2{2},3{1}');
$repohistoryTable->revision->range('rev001,rev002,rev003,rev004,rev005');
$repohistoryTable->comment->range('测试提交{1-5}');
$repohistoryTable->committer->range('admin');
$repohistoryTable->gen(5);

$relationTable = zenData('relation');
$relationTable->id->range('1-6');
$relationTable->AType->range('revision');
$relationTable->AID->range('1,2,3,1,2,3');
$relationTable->relation->range('commit');
$relationTable->BType->range('story,bug,task,story,bug,task');
$relationTable->BID->range('1,2,3,4,5,6');
$relationTable->gen(6);

su('admin');

$repoTest = new repoModelTest();

r($repoTest->unlinkTest(1, 'rev001', 'story', 1)) && p('') && e('success');
r($repoTest->unlinkTest(1, 'rev002', 'bug', 2)) && p('') && e('success');
r($repoTest->unlinkTest(2, 'rev003', 'task', 3)) && p('') && e('success');
r($repoTest->unlinkTest(1, 'nonexistent', 'story', 1)) && p('') && e('not_found');
r($repoTest->unlinkTest(1, 'rev001', 'invalidtype', 1)) && p('') && e('no_relation');
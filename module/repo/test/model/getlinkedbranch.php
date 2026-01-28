#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getLinkedBranch();
timeout=0
cid=18068

- 执行repoTest模块的getLinkedBranchTest方法，参数是1, 'story', 1 
 - 第0条的BType属性 @master
 - 第0条的BID属性 @1
 - 第0条的AType属性 @story
- 执行repoTest模块的getLinkedBranchTest方法，参数是0, 'task', 0, true  @8
- 执行repoTest模块的getLinkedBranchTest方法，参数是0, '', 2, true  @3
- 执行repoTest模块的getLinkedBranchTest方法，参数是999, 'story', 999, true  @0
- 执行repoTest模块的getLinkedBranchTest方法，参数是0, '', 0, true  @15

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('relation');
$table->AType->range('story{5}, task{8}, bug{2}');
$table->AID->range('1-15');
$table->relation->range('linkrepobranch');
$table->BType->range('master{5}, develop{4}, release{3}, main{2}, feature{1}');
$table->BID->range('1-5');
$table->product->range('0');
$table->project->range('0');
$table->execution->range('0');
$table->gen(15);

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getLinkedBranchTest(1, 'story', 1)) && p('0:BType,BID,AType') && e('master,1,story');
r($repoTest->getLinkedBranchTest(0, 'task', 0, true)) && p() && e('8');
r($repoTest->getLinkedBranchTest(0, '', 2, true)) && p() && e('3');
r($repoTest->getLinkedBranchTest(999, 'story', 999, true)) && p() && e('0');
r($repoTest->getLinkedBranchTest(0, '', 0, true)) && p() && e('15');
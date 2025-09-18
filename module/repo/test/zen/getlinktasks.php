#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkTasks();
timeout=0
cid=0

- 执行repoTest模块的getLinkTasksTest方法，参数是1, 'rev123', 'all', array  @4
- 执行repoTest模块的getLinkTasksTest方法，参数是1, 'rev456', 'all', array  @0
- 执行repoTest模块的getLinkTasksTest方法，参数是1, 'rev789', 'bysearch', array  @2
- 执行repoTest模块的getLinkTasksTest方法，参数是2, 'rev999', 'all', array  @4
- 执行repoTest模块的getLinkTasksTest方法，参数是3, 'rev000', 'all', array  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_getlinktasks.unittest.class.php';

$table = zenData('task');
$table->id->range('1-20');
$table->project->range('1-5');
$table->execution->range('1-3');
$table->name->range('任务1,任务2,任务3,任务4,任务5');
$table->status->range('wait{5},doing{5},done{5},closed{5}');
$table->type->range('design,devel,test,study,discuss');
$table->deleted->range('0');
$table->gen(20);

$repoTable = zenData('repo');
$repoTable->id->range('1-5');
$repoTable->name->range('版本库1,版本库2,版本库3,版本库4,版本库5');
$repoTable->gen(5);

$relationTable = zenData('relation');
$relationTable->id->range('1-10');
$relationTable->product->range('1-3');
$relationTable->project->range('1-3');
$relationTable->AType->range('task');
$relationTable->AID->range('1,3,5,7,9');
$relationTable->BType->range('commit');
$relationTable->BID->range('1,2,3,4,5');
$relationTable->extra->range('1');
$relationTable->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->gen(5);

su('admin');

$repoTest = new repoZenTest();

$pager = new stdClass();
$pager->recPerPage = 20;
$pager->pageID = 1;

r(count($repoTest->getLinkTasksTest(1, 'rev123', 'all', array(1 => (object)array('id' => 1, 'name' => '产品1')), 'id_desc', $pager, 0, array(1 => '执行1', 2 => '执行2')))) && p() && e('4');
r(count($repoTest->getLinkTasksTest(1, 'rev456', 'all', array(1 => (object)array('id' => 1, 'name' => '产品1')), 'id_desc', $pager, 0, array()))) && p() && e('0');
r(count($repoTest->getLinkTasksTest(1, 'rev789', 'bysearch', array(1 => (object)array('id' => 1, 'name' => '产品1')), 'id_desc', $pager, 1, array(1 => '执行1')))) && p() && e('2');
r(count($repoTest->getLinkTasksTest(2, 'rev999', 'all', array(2 => (object)array('id' => 2, 'name' => '产品2')), 'id_asc', $pager, 0, array(3 => '执行3', 4 => '执行4')))) && p() && e('4');
r(count($repoTest->getLinkTasksTest(3, 'rev000', 'all', array(3 => (object)array('id' => 3, 'name' => '产品3')), 'id_desc', $pager, 0, array(5 => '执行5')))) && p() && e('2');
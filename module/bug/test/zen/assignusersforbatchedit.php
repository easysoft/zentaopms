#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignUsersForBatchEdit();
timeout=0
cid=0

- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是$bugs1, $productIdList1, $branchTagOption1, 'execution'
 - 属性users @20
 - 属性productMembers @1
 - 属性projectMembers @1
 - 属性executionMembers @0
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是$bugs2, $productIdList2, $branchTagOption2, 'project'
 - 属性users @20
 - 属性productMembers @1
 - 属性projectMembers @1
 - 属性executionMembers @0
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是$bugs3, $productIdList3, $branchTagOption3, 'qa'
 - 属性users @20
 - 属性productMembers @1
 - 属性projectMembers @1
 - 属性executionMembers @0
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是$bugs4, $productIdList4, $branchTagOption4, 'execution'
 - 属性users @20
 - 属性productMembers @2
 - 属性projectMembers @2
 - 属性executionMembers @0
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是$bugs5, $productIdList5, $branchTagOption5, 'qa'
 - 属性users @20
 - 属性productMembers @2
 - 属性projectMembers @2
 - 属性executionMembers @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-5');
$bug->project->range('11-15');
$bug->execution->range('101-105');
$bug->branch->range('0');
$bug->gen(10);

$user = zenData('user');
$user->id->range('1-20');
$user->account->range('user1,user2,user3,user4,user5,user6,user7,user8,user9,user10,user11,user12,user13,user14,user15,user16,user17,user18,user19,user20');
$user->realname->range('用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9,用户10,用户11,用户12,用户13,用户14,用户15,用户16,用户17,用户18,用户19,用户20');
$user->deleted->range('0');
$user->gen(20);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->gen(5);

$project = zenData('project');
$project->id->range('11-15,101-105');
$project->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5');
$project->type->range('project{5},stage{5}');
$project->parent->range('0{5},11,12,13,14,15');
$project->multiple->range('1');
$project->gen(10);

$team = zenData('team');
$team->id->range('1-20');
$team->root->range('11-15{4},101-105{4}');
$team->type->range('project{10},execution{10}');
$team->account->range('user1,user2,user3,user4,user5,user6,user7,user8,user9,user10,user11,user12,user13,user14,user15,user16,user17,user18,user19,user20');
$team->gen(20);

su('admin');

$bugTest = new bugZenTest();

$bugs1 = array(
    (object)array('id' => 1, 'project' => 11, 'product' => 1, 'execution' => 101, 'branch' => 0),
    (object)array('id' => 2, 'project' => 11, 'product' => 1, 'execution' => 101, 'branch' => 0)
);
$productIdList1 = array(1 => 1);
$branchTagOption1 = array(1 => array(0 => array('name' => 'trunk')));

$bugs2 = array(
    (object)array('id' => 3, 'project' => 12, 'product' => 2, 'execution' => 102, 'branch' => 0),
    (object)array('id' => 4, 'project' => 12, 'product' => 2, 'execution' => 102, 'branch' => 0)
);
$productIdList2 = array(2 => 2);
$branchTagOption2 = array(2 => array(0 => array('name' => 'trunk')));

$bugs3 = array(
    (object)array('id' => 5, 'project' => 13, 'product' => 3, 'execution' => 103, 'branch' => 0)
);
$productIdList3 = array(3 => 3);
$branchTagOption3 = array(3 => array(0 => array('name' => 'trunk')));

$bugs4 = array(
    (object)array('id' => 6, 'project' => 11, 'product' => 1, 'execution' => 101, 'branch' => 0),
    (object)array('id' => 7, 'project' => 12, 'product' => 2, 'execution' => 102, 'branch' => 0)
);
$productIdList4 = array(1 => 1, 2 => 2);
$branchTagOption4 = array(1 => array(0 => array('name' => 'trunk')), 2 => array(0 => array('name' => 'trunk')));

$bugs5 = array();
$productIdList5 = array();
$branchTagOption5 = array();

r($bugTest->assignUsersForBatchEditTest($bugs1, $productIdList1, $branchTagOption1, 'execution')) && p('users,productMembers,projectMembers,executionMembers') && e('20,1,1,0');
r($bugTest->assignUsersForBatchEditTest($bugs2, $productIdList2, $branchTagOption2, 'project')) && p('users,productMembers,projectMembers,executionMembers') && e('20,1,1,0');
r($bugTest->assignUsersForBatchEditTest($bugs3, $productIdList3, $branchTagOption3, 'qa')) && p('users,productMembers,projectMembers,executionMembers') && e('20,1,1,0');
r($bugTest->assignUsersForBatchEditTest($bugs4, $productIdList4, $branchTagOption4, 'execution')) && p('users,productMembers,projectMembers,executionMembers') && e('20,2,2,0');
r($bugTest->assignUsersForBatchEditTest($bugs5, $productIdList5, $branchTagOption5, 'qa')) && p('users,productMembers,projectMembers,executionMembers') && e('20,2,2,0');
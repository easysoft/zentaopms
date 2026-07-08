#!/usr/bin/env php
<?php
/**

title=测试 bugZen::buildCreateForm();
timeout=0
cid=15427

- 测试构造创建Bug的数据
 - 属性title @正常产品1-提Bug
 - 属性productMembers @6
- 测试构造创建Bug的数据
 - 属性productName @正常产品1
 - 属性productsCount @1
- 测试构造创建Bug的数据
 - 属性branchesCount @1
 - 属性buildsCount @1
- 测试构造创建Bug的数据
 - 属性moduleOptionMenuCount @1
 - 属性resultFilesCount @0
- 测试构造创建Bug的数据
 - 属性plansCount @0
 - 属性casesCount @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(1);
zenData('team')->gen(0);
zenData('branch')->gen(0);
zenData('build')->gen(0);
zenData('module')->gen(0);
zenData('productplan')->gen(0);
zenData('case')->gen(0);
zenData('bug')->gen(10);
zenData('user')->gen(5);
su('admin');

$bug = new stdclass();
$bug->id          = 1;
$bug->title       = 'Bug1';
$bug->productID   = 1;
$bug->product     = 1;
$bug->branch      = 0;
$bug->moduleID    = 0;
$bug->projectID   = 0;
$bug->executionID = 0;
$bug->type        = 'codeerror';
$bug->status      = 'active';
$bug->assignedTo  = '';
$bug->openedBuild = 'trunk';
$bug->story       = 0;
$bug->storyTitle  = '';
$bug->testtask    = 0;
$bug->openedBy    = 'admin';
$bug->resolvedBy  = '';
$bug->closedBy    = '';

$bugTest = new bugZenTest();
r($bugTest->buildCreateFormTest($bug)) && p('title,productMembers')                   && e('正常产品1-提Bug,6'); // 测试构造创建Bug的数据
r($bugTest->buildCreateFormTest($bug)) && p('productName,productsCount')              && e('正常产品1,1');       // 测试构造创建Bug的数据
r($bugTest->buildCreateFormTest($bug)) && p('branchesCount,buildsCount')              && e('1,1');               // 测试构造创建Bug的数据
r($bugTest->buildCreateFormTest($bug)) && p('moduleOptionMenuCount,resultFilesCount') && e('1,0');               // 测试构造创建Bug的数据
r($bugTest->buildCreateFormTest($bug)) && p('plansCount,casesCount')                  && e('0,0');               // 测试构造创建Bug的数据

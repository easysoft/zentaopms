#!/usr/bin/env php
<?php
/**

title=测试 bugZen::buildEditForm();
timeout=0
cid=15427

- 测试构造编辑Bug的数据
 - 属性bug @1
 - 属性product @1
 - 属性projectID @0
 - 属性executions @0
 - 属性branchTagOption @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(1);
zenData('bug')->gen(10);
zenData('user')->gen(5);
su('admin');

$bug = new stdclass();
$bug->id          = 1;
$bug->title       = 'Bug1';
$bug->product     = 1;
$bug->branch      = 0;
$bug->module      = 0;
$bug->project     = 0;
$bug->execution   = 0;
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
r($bugTest->buildEditFormTest($bug)) && p('bug,product,projectID,executions,branchTagOption') && e('1,1,0,0,0'); // 测试构造编辑Bug的数据

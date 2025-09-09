#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1-10');
$program->name->range('1-10')->prefix('项目集');
$program->type->range('program');
$program->status->range('wait{3},doing{3},suspended{2},closed{2}');
$program->openedBy->range('admin');
$program->openedDate->range('2023-01-01 000000:0')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$program->PM->range('admin');
$program->path->range('1-10')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->parent->range('0');
$program->deleted->range('0');
$program->gen(10);

/**

title=测试 programModel::suspend();
timeout=0
cid=0

- 步骤1：正常挂起项目集 @1
- 步骤2：挂起已挂起的项目集 @1
- 步骤3：挂起不存在的项目集 @0
- 步骤4：使用空评论挂起项目集 @1
- 步骤5：带评论挂起项目集 @1

*/

$programTest = new programTest();

r($programTest->suspendTest(1, array('comment' => '测试挂起', 'uid' => ''))) && p() && e('1'); // 步骤1：正常挂起项目集
r($programTest->suspendTest(7, array('comment' => '重复挂起', 'uid' => ''))) && p() && e('1'); // 步骤2：挂起已挂起的项目集
r($programTest->suspendTest(999, array('comment' => '不存在项目集', 'uid' => ''))) && p() && e('0'); // 步骤3：挂起不存在的项目集
r($programTest->suspendTest(2, array('comment' => '', 'uid' => ''))) && p() && e('1'); // 步骤4：使用空评论挂起项目集
r($programTest->suspendTest(3, array('comment' => '带评论的挂起操作', 'uid' => ''))) && p() && e('1'); // 步骤5：带评论挂起项目集
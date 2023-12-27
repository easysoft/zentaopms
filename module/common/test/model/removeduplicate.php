#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$story = zdTable('story');
$story->id->range('1');
$story->title->range('teststory');
$story->gen(1);

$case = zdTable('case');
$case->id->range('1');
$case->title->range('testcase');
$case->gen(1);

$task = zdTable('task');
$task->id->range('1');
$task->name->range('testtask');
$task->gen(1);

$bug = zdTable('bug');
$bug->id->range('1');
$bug->title->range('testbug');
$bug->gen(1);

$doc = zdTable('doc');
$doc->id->range('1');
$doc->title->range('testdoc');
$doc->gen(1);

/**

title=测试 commonModel->removeDuplicate();
timeout=0
cid=1

- 测试是否是重复需求
 - 属性stop @1
 - 属性duplicate @1
- 有条件情况下，测试是否是重复需求。属性stop @~~
- 测试是否是重复用例
 - 属性stop @1
 - 属性duplicate @1
- 有条件情况下，测试是否是重复用例。属性stop @~~
- 测试是否是重复任务
 - 属性stop @1
 - 属性duplicate @1
- 有条件情况下，测试是否是重复用例。属性stop @~~
- 测试是否是重复Bug
 - 属性stop @1
 - 属性duplicate @1
- 有条件情况下，测试是否是重复Bug属性stop @~~
- 测试是否是重复文档
 - 属性stop @1
 - 属性duplicate @1
- 有条件情况下，测试是否是重复文档属性stop @~~
- 测试批量重复需求 @story1
- 有条件情况下，测试批量重复需求 @2
- 测试批量重复用例 @case1
- 有条件情况下，测试批量重复用例 @2
- 测试批量重复任务 @task1
- 有条件情况下，测试批量重复任务 @2
- 测试批量重复Bug @bug1
- 有条件情况下，测试批量重复Bug @2
- 测试批量重复文档 @doc1
- 有条件情况下，测试批量重复文档 @2

*/

global $tester;
$tester->loadModel('common');

$now = date('Y-m-d H:i:s', time() - 5);
$tester->common->dao->update(TABLE_STORY)->set('openedDate')->eq($now)->exec();
$tester->common->dao->update(TABLE_CASE)->set('openedDate')->eq($now)->exec();
$tester->common->dao->update(TABLE_TASK)->set('openedDate')->eq($now)->exec();
$tester->common->dao->update(TABLE_BUG)->set('openedDate')->eq($now)->exec();
$tester->common->dao->update(TABLE_DOC)->set('addedDate')->eq($now)->exec();

$story = new stdclass();
$story->title = 'teststory';
$story->type  = 'requirement';
r($tester->common->removeDuplicate('story', $story)) && p('stop,duplicate') && e('1,1'); //测试是否是重复需求
r($tester->common->removeDuplicate('story', $story, 'id!=1')) && p('stop') && e('~~');   //有条件情况下，测试是否是重复需求。

$case = new stdclass();
$case->title = 'testcase';
r($tester->common->removeDuplicate('case', $case)) && p('stop,duplicate') && e('1,1'); //测试是否是重复用例
r($tester->common->removeDuplicate('case', $case, 'id!=1')) && p('stop') && e('~~');   //有条件情况下，测试是否是重复用例。

$task = new stdclass();
$task->name = 'testtask';
r($tester->common->removeDuplicate('task', $task)) && p('stop,duplicate') && e('1,1'); //测试是否是重复任务
r($tester->common->removeDuplicate('task', $task, 'id!=1')) && p('stop') && e('~~');   //有条件情况下，测试是否是重复用例。

$bug = new stdclass();
$bug->title = 'testbug';
r($tester->common->removeDuplicate('bug', $bug)) && p('stop,duplicate') && e('1,1'); //测试是否是重复Bug
r($tester->common->removeDuplicate('bug', $bug, 'id!=1')) && p('stop') && e('~~');   //有条件情况下，测试是否是重复Bug

$doc = new stdclass();
$doc->title = 'testdoc';
r($tester->common->removeDuplicate('doc', $doc)) && p('stop,duplicate') && e('1,1'); //测试是否是重复文档
r($tester->common->removeDuplicate('doc', $doc, 'id!=1')) && p('stop') && e('~~');   //有条件情况下，测试是否是重复文档

$story = new stdclass();
$story->title = array('teststory', 'story1');
$result = $tester->common->removeDuplicate('story', $story);
r($result['data']->title[1]) && p() && e('story1');  //测试批量重复需求

$story->title = array('teststory', 'story1');
$result = $tester->common->removeDuplicate('story', $story, 'id!=1');
r(count($result['data']->title)) && p() && e('2'); //有条件情况下，测试批量重复需求

$case = new stdclass();
$case->title = array('testcase', 'case1');
$result = $tester->common->removeDuplicate('case', $case);
r($result['data']->title[1]) && p() && e('case1'); //测试批量重复用例

$case->title = array('testcase', 'case1');
$result = $tester->common->removeDuplicate('case', $case, 'id!=1');
r(count($result['data']->title)) && p() && e('2'); //有条件情况下，测试批量重复用例

$task = new stdclass();
$task->name = array('testtask', 'task1');
$result = $tester->common->removeDuplicate('task', $task);
r($result['data']->name[1]) && p() && e('task1'); //测试批量重复任务

$task->name = array('testtask', 'task1');
$result = $tester->common->removeDuplicate('task', $task, 'id!=1');
r(count($result['data']->name)) && p() && e('2'); //有条件情况下，测试批量重复任务

$bug = new stdclass();
$bug->title = array('testbug', 'bug1');
$result = $tester->common->removeDuplicate('bug', $bug);
r($result['data']->title[1]) && p() && e('bug1'); //测试批量重复Bug

$bug->title = array('testbug', 'bug1');
$result = $tester->common->removeDuplicate('bug', $bug, 'id!=1');
r(count($result['data']->title)) && p() && e('2'); //有条件情况下，测试批量重复Bug

$doc = new stdclass();
$doc->title = array('testdoc', 'doc1');
$result = $tester->common->removeDuplicate('doc', $doc);
r($result['data']->title[1]) && p() && e('doc1'); //测试批量重复文档

$doc->title = array('testdoc', 'doc1');
$result = $tester->common->removeDuplicate('doc', $doc, 'id!=1');
r(count($result['data']->title)) && p() && e('2'); //有条件情况下，测试批量重复文档

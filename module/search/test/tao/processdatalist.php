#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDataList();
timeout=0
cid=0

- 步骤1：处理 task 数据时会把 opened 动作写回 addedDate @2026-07-01 10:00:00
- 步骤2：处理 task 数据时会把最后一次动作写回 editedDate @2026-07-02 11:00:00
- 步骤3：处理 task 数据时会追加附件标题到 comment @1
- 步骤4：处理 case 数据时会把 opened 动作写回 addedDate @2026-07-03 10:00:00
- 步骤5：处理 case 数据时只拼接当前版本的步骤描述 @1
- 步骤6：处理 case 数据时会追加附件标题到 comment @1
- 步骤7：处理 case 数据时会拼接当前版本的预期结果 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$tester->dao->delete()->from(TABLE_TASK)->where('id')->eq(9201)->exec();
$tester->dao->delete()->from(TABLE_ACTION)->where('id')->in('92011,92012,93011')->exec();
$tester->dao->delete()->from(TABLE_FILE)->where('id')->in('92021,93021')->exec();
$tester->dao->delete()->from(TABLE_CASE)->where('id')->eq(9301)->exec();
$tester->dao->delete()->from(TABLE_CASESTEP)->where('id')->in('93031,93032,93033')->exec();

$tester->dao->insert(TABLE_TASK)->data(array(
    'id'             => 9201,
    'project'        => 11,
    'execution'      => 101,
    'module'         => 0,
    'story'          => 0,
    'storyVersion'   => 1,
    'design'         => 0,
    'designVersion'  => 0,
    'fromBug'        => 0,
    'name'           => 'Task A',
    'type'           => 'devel',
    'pri'            => 1,
    'estimate'       => 1,
    'consumed'       => 0,
    'left'           => 1,
    'deadline'       => '2026-07-20',
    'status'         => 'wait',
    'subStatus'      => '',
    'color'          => '',
    'desc'           => 'Task desc',
    'version'        => 1,
    'openedBy'       => 'admin',
    'openedDate'     => '2026-07-01 09:00:00',
    'assignedTo'     => '',
    'assignedDate'   => '2026-07-01 09:00:00',
    'estStarted'     => '2026-07-01',
    'realStarted'    => '0000-00-00',
    'finishedBy'     => '',
    'finishedList'   => '',
    'canceledBy'     => '',
    'closedBy'       => '',
    'closedReason'   => '',
    'closedDate'     => null,
    'lastEditedBy'   => 'admin',
    'lastEditedDate' => '2026-07-01 09:00:00',
    'deleted'        => '0',
    'vision'         => 'rnd',
))->exec();
$tester->dao->insert(TABLE_ACTION)->data(array('id' => 92011, 'objectType' => 'task', 'objectID' => 9201, 'product' => ',1,', 'project' => 11, 'actor' => 'admin', 'action' => 'opened', 'date' => '2026-07-01 10:00:00', 'comment' => 'first note', 'extra' => '', 'read' => '0'))->exec();
$tester->dao->insert(TABLE_ACTION)->data(array('id' => 92012, 'objectType' => 'task', 'objectID' => 9201, 'product' => ',1,', 'project' => 11, 'actor' => 'admin', 'action' => 'edited', 'date' => '2026-07-02 11:00:00', 'comment' => 'second note', 'extra' => '', 'read' => '0'))->exec();
$tester->dao->insert(TABLE_FILE)->data(array('id' => 92021, 'pathname' => '', 'title' => 'spec', 'extension' => 'txt', 'size' => 100, 'objectType' => 'task', 'objectID' => 9201, 'addedBy' => 'admin', 'addedDate' => '2026-07-02', 'downloads' => 0, 'extra' => '0', 'deleted' => '0'))->exec();

$tester->dao->insert(TABLE_CASE)->data(array(
    'id'              => 9301,
    'product'         => 1,
    'branch'          => 0,
    'execution'       => 101,
    'lib'             => 0,
    'module'          => 0,
    'path'            => '0',
    'story'           => 0,
    'storyVersion'    => 1,
    'title'           => 'Case A',
    'precondition'    => 'pre',
    'keywords'        => 'key',
    'pri'             => 1,
    'type'            => 'feature',
    'auto'            => 'no',
    'frame'           => '',
    'stage'           => 'unittest',
    'howRun'          => '',
    'scriptedBy'      => '',
    'scriptedDate'    => null,
    'scriptStatus'    => '',
    'scriptLocation'  => '',
    'status'          => 'wait',
    'subStatus'       => '',
    'color'           => '',
    'frequency'       => 1,
    'order'           => 5,
    'openedBy'        => 'admin',
    'openedDate'      => '2026-07-01 09:00:00',
    'reviewedBy'      => 'admin',
    'reviewedDate'    => '2026-07-01 09:00:00',
    'lastEditedBy'    => 'admin',
    'lastEditedDate'  => '2026-07-01 09:00:00',
    'version'         => 2,
    'linkCase'        => 0,
    'fromBug'         => 0,
    'fromCaseID'      => 0,
    'fromCaseVersion' => 1,
    'deleted'         => '0',
    'lastRunner'      => 'admin',
    'lastRunDate'     => '2026-07-01 09:00:00',
    'lastRunResult'   => 'pass',
))->exec();
$tester->dao->insert(TABLE_CASESTEP)->data(array('id' => 93031, 'parent' => 0, 'case' => 9301, 'version' => 1, 'type' => 'step', 'desc' => 'old desc', 'expect' => 'old expect'))->exec();
$tester->dao->insert(TABLE_CASESTEP)->data(array('id' => 93032, 'parent' => 0, 'case' => 9301, 'version' => 2, 'type' => 'step', 'desc' => 'new desc 1', 'expect' => 'new expect 1'))->exec();
$tester->dao->insert(TABLE_CASESTEP)->data(array('id' => 93033, 'parent' => 0, 'case' => 9301, 'version' => 2, 'type' => 'step', 'desc' => 'new desc 2', 'expect' => 'new expect 2'))->exec();
$tester->dao->insert(TABLE_ACTION)->data(array('id' => 93011, 'objectType' => 'case', 'objectID' => 9301, 'product' => ',1,', 'project' => 11, 'actor' => 'admin', 'action' => 'opened', 'date' => '2026-07-03 10:00:00', 'comment' => 'case note', 'extra' => '', 'read' => '0'))->exec();
$tester->dao->insert(TABLE_FILE)->data(array('id' => 93021, 'pathname' => '', 'title' => 'casefile', 'extension' => 'md', 'size' => 100, 'objectType' => 'case', 'objectID' => 9301, 'addedBy' => 'admin', 'addedDate' => '2026-07-02', 'downloads' => 0, 'extra' => '0', 'deleted' => '0'))->exec();

su('admin');

$search = new searchTaoTest();
$field  = (object) array('addedDate' => 'openedDate', 'editedDate' => 'lastEditedDate');

$taskDataList = array(9201 => $tester->dao->select('*')->from(TABLE_TASK)->where('id')->eq(9201)->fetch());
$caseDataList = array(9301 => $tester->dao->select('*')->from(TABLE_CASE)->where('id')->eq(9301)->fetch());

$taskResult = $search->processDataListTest('task', $field, $taskDataList);
$caseResult = $search->processDataListTest('case', $field, $caseDataList);

r($taskResult[9201]->openedDate)                               && p() && e('2026-07-01 10:00:00'); // 步骤1：处理 task 数据时会把 opened 动作写回 addedDate
r($taskResult[9201]->lastEditedDate)                           && p() && e('2026-07-02 11:00:00'); // 步骤2：处理 task 数据时会把最后一次动作写回 editedDate
r(strpos($taskResult[9201]->comment, 'spec.txt') !== false)    && p() && e('1');                   // 步骤3：处理 task 数据时会追加附件标题到 comment
r($caseResult[9301]->openedDate)                               && p() && e('2026-07-03 10:00:00'); // 步骤4：处理 case 数据时会把 opened 动作写回 addedDate
r(strpos($caseResult[9301]->desc, 'old desc') === false)       && p() && e('1');                   // 步骤5：处理 case 数据时只拼接当前版本的步骤描述
r(strpos($caseResult[9301]->comment, 'casefile.md') !== false) && p() && e('1');                   // 步骤6：处理 case 数据时会追加附件标题到 comment
r(strpos($caseResult[9301]->expect, 'new expect 1') !== false) && p() && e('1');                   // 步骤7：处理 case 数据时会拼接当前版本的预期结果

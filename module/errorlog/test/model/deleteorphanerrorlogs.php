#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteOrphanErrorLogs();
timeout=0
cid=0

- 执行errorlog模块的deleteOrphanErrorLogsTest方法，参数是全部5个md5 @1
- 执行清理后错误本体表记录数量 @3
- 执行清理后请求记录表记录数量 @3
- 执行清理后无引用的a4、a5错误本体数量 @0
- 执行清理后有引用的a1错误本体数量 @1
- 执行errorlog模块的deleteOrphanErrorLogsTest方法，参数是array('a1', 'a2') @1
- 有引用的错误本体不被误删，错误本体表记录数量 @3
- 执行errorlog模块的deleteOrphanErrorLogsTest方法，参数是空数组 @1
- 传入空数组清理后错误本体表记录数量 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlog = zenData('errorlog');
$errorlog->id->setNull();
$errorlog->md5->range('a1,a2,a3,a4,a5');
$errorlog->file->range('module/bug/list.php{5}');
$errorlog->line->range('1,2,3,4,5');
$errorlog->level->range('2{5}');
$errorlog->message->range('msg-1,msg-2,msg-3,msg-4,msg-5');
$errorlog->gen(5);

$errorlogreq = zenData('errorlogreq');
$errorlogreq->id->range('1-3');
$errorlogreq->requestID->range('req-001{3}');
$errorlogreq->md5->range('a1,a2,a3');
$errorlogreq->module->range('bug{3}');
$errorlogreq->gen(3);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteOrphanErrorLogsTest(array('a1', 'a2', 'a3', 'a4', 'a5'))) && p() && e('1');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->fetchAll('', false))) && p() && e('3');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOGREQ)->fetchAll('', false))) && p() && e('3');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->where('md5')->in(array('a4', 'a5'))->fetchAll('', false))) && p() && e('0');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->where('md5')->eq('a1')->fetchAll('', false))) && p() && e('1');
r($errorlogModel->deleteOrphanErrorLogsTest(array('a1', 'a2'))) && p() && e('1');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->fetchAll('', false))) && p() && e('3');
r($errorlogModel->deleteOrphanErrorLogsTest(array())) && p() && e('1');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->fetchAll('', false))) && p() && e('3');

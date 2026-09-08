#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByDate();
timeout=0
cid=0

- 执行errorlog模块的deleteByDateTest方法，参数是2026-06-01 00:00:00 @1
- 执行errorlog模块的getListTest方法，返回列表数量 @3
- 执行errorlog模块的getListTest方法，第1条的createdDate属性 @2026-08-01 00:00:00
- 执行errorlog模块的getListTest方法，第1条的requestID属性 @req-002
- 执行errorlog模块删除后错误本体表记录数量 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlog = zenData('errorlog');
$errorlog->id->setNull();
$errorlog->md5->range('d1,d2,d3,d4,d5');
$errorlog->file->range('module/bug/list.php{5}');
$errorlog->line->range('1,2,3,4,5');
$errorlog->level->range('2{5}');
$errorlog->message->range('msg-1,msg-2,msg-3,msg-4,msg-5');
$errorlog->gen(5);

$errorlogreq = zenData('errorlogreq');
$errorlogreq->id->range('1-5');
$errorlogreq->requestID->range('req-001{2},req-002{3}');
$errorlogreq->md5->range('d1,d2,d3,d4,d5');
$errorlogreq->module->range('bug{3},task{2}');
$errorlogreq->createdDate->range('`2026-01-01 00:00:00`,`2026-01-01 00:00:00`,`2026-08-01 00:00:00`,`2026-08-01 00:00:00`,`2026-08-01 00:00:00`');
$errorlogreq->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByDateTest('2026-06-01 00:00:00')) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('3');
r($errorlogModel->getListTest()) && p('0:createdDate') && e('2026-08-01 00:00:00');
r($errorlogModel->getListTest()) && p('0:requestID') && e('req-002');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->fetchAll('', false))) && p() && e('3');

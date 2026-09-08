#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByIDs();
timeout=0
cid=0

- 执行errorlog模块的deleteByIDsTest方法，参数是array(2, 3) @1
- 执行errorlog模块的getListTest方法，返回列表数量 @2
- 执行errorlog模块的getByIDTest方法，参数是2 @0
- 执行errorlog模块的getByIDTest方法，参数是3 @0
- 执行errorlog模块批量删除后错误本体表记录数量 @2
- 执行errorlog模块的getByIDTest方法，参数是4，md5属性 @md5-4
- 执行errorlog模块的deleteByIDsTest方法，参数是array(1, 'abc', 4) @1
- 执行errorlog模块的getListTest方法，返回列表数量 @0
- 执行errorlog模块再次删除后错误本体表记录数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlog = zenData('errorlog');
$errorlog->id->setNull();
$errorlog->md5->range('md5-1,md5-2,md5-3,md5-4');
$errorlog->file->range('module/bug/list.php{4}');
$errorlog->line->range('1,2,3,4');
$errorlog->level->range('2{4}');
$errorlog->message->range('msg-1,msg-2,msg-3,msg-4');
$errorlog->gen(4);

$errorlogreq = zenData('errorlogreq');
$errorlogreq->id->range('1-4');
$errorlogreq->requestID->range('req-001{4}');
$errorlogreq->md5->range('md5-1,md5-2,md5-3,md5-4');
$errorlogreq->module->range('bug{4}');
$errorlogreq->gen(4);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByIDsTest(array(2, 3))) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('2');
r($errorlogModel->getByIDTest(2)) && p() && e('0');
r($errorlogModel->getByIDTest(3)) && p() && e('0');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->fetchAll('', false))) && p() && e('2');
r($errorlogModel->getByIDTest(4)) && p('md5') && e('md5-4');
r($errorlogModel->deleteByIDsTest(array(1, 'abc', 4))) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('0');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->fetchAll('', false))) && p() && e('0');

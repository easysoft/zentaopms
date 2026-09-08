#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::deleteByID();
timeout=0
cid=0

- 执行errorlog模块的deleteByIDTest方法，参数是2 @1
- 执行errorlog模块的getListTest方法，返回列表数量 @2
- 执行errorlog模块的getByIDTest方法，参数是2 @0
- 执行errorlog模块的getByIDTest方法，参数是1，md5属性 @md5-1
- 执行errorlog模块删除后错误本体表记录数量 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlog = zenData('errorlog');
$errorlog->id->setNull();
$errorlog->md5->range('md5-1,md5-2,md5-3');
$errorlog->file->range('module/bug/list.php{3}');
$errorlog->line->range('1,2,3');
$errorlog->level->range('2{3}');
$errorlog->message->range('msg-1,msg-2,msg-3');
$errorlog->gen(3);

$errorlogreq = zenData('errorlogreq');
$errorlogreq->id->range('1-3');
$errorlogreq->requestID->range('req-001{3}');
$errorlogreq->md5->range('md5-1,md5-2,md5-3');
$errorlogreq->module->range('bug{3}');
$errorlogreq->gen(3);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->deleteByIDTest(2)) && p() && e('1');
r(count($errorlogModel->getListTest())) && p() && e('2');
r($errorlogModel->getByIDTest(2)) && p() && e('0');
r($errorlogModel->getByIDTest(1)) && p('md5') && e('md5-1');
r(count($errorlogModel->instance->dao->select('md5')->from(TABLE_ERRORLOG)->fetchAll('', false))) && p() && e('2');

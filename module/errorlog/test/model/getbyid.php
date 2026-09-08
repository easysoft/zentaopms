#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getByID();
timeout=0
cid=0

- 按ID查询第1条日志的requestID属性 @req-001
- 按ID查询第1条日志的module属性 @bug
- 按ID查询第4条日志的requestID属性 @req-002
- 按ID查询第4条日志的module属性 @task
- ID为0时返回空 @0
- ID为负数时返回空 @0
- 不存在的ID返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlog = zenData('errorlog');
$errorlog->id->setNull();
$errorlog->md5->range('m1,m2,m3,m4,m5');
$errorlog->file->range('module/bug/list.php{5}');
$errorlog->line->range('1,2,3,4,5');
$errorlog->level->range('2{5}');
$errorlog->message->range('msg-1,msg-2,msg-3,msg-4,msg-5');
$errorlog->gen(5);

$errorlogreq = zenData('errorlogreq');
$errorlogreq->id->range('1-5');
$errorlogreq->requestID->range('req-001{2},req-002{3}');
$errorlogreq->md5->range('m1,m2,m3,m4,m5');
$errorlogreq->module->range('bug{3},task{2}');
$errorlogreq->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->getByIDTest(1)) && p('requestID') && e('req-001');
r($errorlogModel->getByIDTest(1)) && p('module') && e('bug');
r($errorlogModel->getByIDTest(4)) && p('requestID') && e('req-002');
r($errorlogModel->getByIDTest(4)) && p('module') && e('task');
r($errorlogModel->getByIDTest(0)) && p() && e('0');
r($errorlogModel->getByIDTest(-1)) && p() && e('0');
r($errorlogModel->getByIDTest(999999)) && p() && e('0');

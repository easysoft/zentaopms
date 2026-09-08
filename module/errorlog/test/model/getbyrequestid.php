#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getByRequestID();
timeout=0
cid=0

- 执行errorlog模块的getByRequestIDTest方法，参数是req-001，返回列表数量 @3
- 执行errorlog模块的getByRequestIDTest方法，参数是req-001，第1条的requestID属性 @req-001
- 执行errorlog模块的getByRequestIDTest方法，参数是req-001，第1条的md5属性 @a1
- 执行errorlog模块的getByRequestIDTest方法，参数是req-001，第1条的message属性 @msg-a1
- 执行errorlog模块的getByRequestIDTest方法，参数是req-001，第3条的md5属性 @a3
- 执行errorlog模块的getByRequestIDTest方法，参数是req-002，返回列表数量 @2
- 执行errorlog模块的getByRequestIDTest方法，参数是req-002，第1条的md5属性 @b1
- 执行errorlog模块的getByRequestIDTest方法，参数是not-exists，返回列表数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlog = zenData('errorlog');
$errorlog->id->setNull();
$errorlog->md5->range('a1,a2,a3,b1,b2');
$errorlog->file->range('module/bug/list.php{5}');
$errorlog->line->range('1,2,3,4,5');
$errorlog->level->range('2{5}');
$errorlog->message->range('msg-a1,msg-a2,msg-a3,msg-b1,msg-b2');
$errorlog->gen(5);

$errorlogreq = zenData('errorlogreq');
$errorlogreq->id->range('1-5');
$errorlogreq->requestID->range('req-001{3},req-002{2}');
$errorlogreq->md5->range('a1,a2,a3,b1,b2');
$errorlogreq->module->range('bug{3},task{2}');
$errorlogreq->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r(count($errorlogModel->getByRequestIDTest('req-001'))) && p() && e('3');
r($errorlogModel->getByRequestIDTest('req-001')) && p('0:requestID') && e('req-001');
r($errorlogModel->getByRequestIDTest('req-001')) && p('0:md5') && e('a1');
r($errorlogModel->getByRequestIDTest('req-001')) && p('0:message') && e('msg-a1');
r($errorlogModel->getByRequestIDTest('req-001')) && p('2:md5') && e('a3');
r(count($errorlogModel->getByRequestIDTest('req-002'))) && p() && e('2');
r($errorlogModel->getByRequestIDTest('req-002')) && p('0:md5') && e('b1');
r(count($errorlogModel->getByRequestIDTest('not-exists'))) && p() && e('0');

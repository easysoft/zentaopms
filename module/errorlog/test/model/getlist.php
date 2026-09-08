#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getList();
timeout=0
cid=0

- 执行errorlog模块的getListTest方法，参数是'', 'id_desc'，返回列表数量 @5
- 执行errorlog模块的getListTest方法，参数是'', 'id_desc'，第1条的requestID属性 @req-002
- 执行errorlog模块的getListTest方法，参数是'', 'id_desc'，第5条的requestID属性 @req-001
- 执行errorlog模块的getListTest方法，参数是'', 'id_desc'，第1条的message属性 @msg-5
- 执行errorlog模块的getListTest方法，参数是'', 'id_desc'，第1条的level属性 @2
- 执行errorlog模块的getListTest方法，参数是'', 'id_desc'且每页2条，返回列表数量 @2
- 执行errorlog模块的getListTest方法，参数是"`module` = 'bug'", 'id_desc'，返回列表数量 @3
- 执行errorlog模块的getListTest方法，参数是"`module` = 'bug'", 'id_desc'，第1条的requestID属性 @req-002
- 执行errorlog模块的getListTest方法，参数是"`module` = 'bug'", 'id_desc'，第3条的requestID属性 @req-001

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$errorlog = zenData('errorlog');
$errorlog->id->setNull();
$errorlog->md5->range('md5-1,md5-2,md5-3,md5-4,md5-5');
$errorlog->file->range('module/bug/list.php{5}');
$errorlog->line->range('1,2,3,4,5');
$errorlog->level->range('2{5}');
$errorlog->message->range('msg-1,msg-2,msg-3,msg-4,msg-5');
$errorlog->gen(5);

$errorlogreq = zenData('errorlogreq');
$errorlogreq->id->range('1-5');
$errorlogreq->requestID->range('req-001{2},req-002{3}');
$errorlogreq->md5->range('md5-1,md5-2,md5-3,md5-4,md5-5');
$errorlogreq->module->range('bug{3},task{2}');
$errorlogreq->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r(count($errorlogModel->getListTest())) && p() && e('5');
r($errorlogModel->getListTest()) && p('0:requestID') && e('req-002');
r($errorlogModel->getListTest()) && p('4:requestID') && e('req-001');
r($errorlogModel->getListTest()) && p('0:message') && e('msg-5');
r($errorlogModel->getListTest()) && p('0:level') && e('2');
r(count($errorlogModel->getListTest('', 'id_desc', 0, 2, 1))) && p() && e('2');
r(count($errorlogModel->getListTest("`module` = 'bug'"))) && p() && e('3');
r($errorlogModel->getListTest("`module` = 'bug'")) && p('0:requestID') && e('req-002');
r($errorlogModel->getListTest("`module` = 'bug'")) && p('2:requestID') && e('req-001');

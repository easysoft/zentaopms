#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getModulePairs();
timeout=0
cid=0

- 获取有错误日志的模块数量 @2
- bug模块存在 @bug
- task模块存在 @task
- 不存在的模块不进入模块列表 @1
- 返回结果为关联数组 @1

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
$errorlogreq->requestID->range('req-001{5}');
$errorlogreq->md5->range('m1,m2,m3,m4,m5');
$errorlogreq->module->range('bug{2},task{3}');
$errorlogreq->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();
$modulePairs   = $errorlogModel->getModulePairsTest();

r(count($modulePairs)) && p() && e('2');
r($modulePairs) && p('bug') && e('bug');
r($modulePairs) && p('task') && e('task');
r(!isset($modulePairs['not-exists'])) && p() && e('1');
r(is_array($modulePairs)) && p() && e('1');

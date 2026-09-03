#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getByID();
timeout=0
cid=0

- 按ID查询第1条日志的requestID和module属性 @req-001,bug
- 按ID查询第4条日志的requestID和module属性 @req-002,task
- ID为0时返回空 @0
- ID为负数时返回空 @0
- 不存在的ID返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{2},req-002{3}');
$errorlog->module->range('bug{3},task{2}');
$errorlog->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->getByIDTest(1)) && p('requestID,module') && e('req-001,bug'); // 按ID查询第1条日志
r($errorlogModel->getByIDTest(4)) && p('requestID,module') && e('req-002,task'); // 按ID查询第4条日志
r($errorlogModel->getByIDTest(0)) && p() && e('0'); // ID为0
r($errorlogModel->getByIDTest(-1)) && p() && e('0'); // ID为负数
r($errorlogModel->getByIDTest(999999)) && p() && e('0'); // 不存在的ID

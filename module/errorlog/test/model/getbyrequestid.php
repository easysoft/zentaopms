#!/usr/bin/env php
<?php

/**

title=测试 errorlogModel::getByRequestID();
timeout=0
cid=0

- 执行errorlog模块的getByRequestIDTest方法，参数是req-001
 - 属性requestID @req-001
 - 属性module @bug
- 执行errorlog模块的getByRequestIDTest方法，参数是req-002
 - 属性requestID @req-002
 - 属性module @task
- 执行errorlog模块的getByRequestIDTest方法，参数是not-exists @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{3},req-002{2}');
$errorlog->module->range('bug{4},task{1}');
$errorlog->gen(5);

su('admin');

$errorlogModel = new errorlogModelTest();

r($errorlogModel->getByRequestIDTest('req-001')) && p('requestID,module') && e('req-001,bug');
r($errorlogModel->getByRequestIDTest('req-002')) && p('requestID,module') && e('req-002,task');
r($errorlogModel->getByRequestIDTest('not-exists')) && p() && e('0');

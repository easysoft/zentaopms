#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getDrill();
timeout=0
cid=0

- 执行pivotTest模块的getDrillTest方法，参数是1, '1', 'status', 'published' 属性field @status
- 执行pivotTest模块的getDrillTest方法，参数是2, '1', 'priority', 'published' 属性field @priority
- 执行pivotTest模块的getDrillTest方法，参数是3, '1', 'severity', 'published' 属性field @severity
- 执行pivotTest模块的getDrillTest方法，参数是4, '1', 'type', 'published' 属性field @type
- 执行pivotTest模块的getDrillTest方法，参数是5, '1', 'openedBy', 'published' 属性field @openedBy
- 执行pivotTest模块的getDrillTest方法，参数是6, '1', 'product', 'published' 属性field @product
- 执行pivotTest模块的getDrillTest方法，参数是7, '1', 'status', 'published' 属性field @status

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('pivot')->gen(10);

$pivotdrill = zenData('pivotdrill');
$pivotdrill->pivot->range('1-7');
$pivotdrill->version->range('1');
$pivotdrill->field->range('status,priority,severity,type,openedBy,product,status');
$pivotdrill->object->range('bug,bug,bug,bug,user,product,bug');
$pivotdrill->whereSql->range('id > 0');
$pivotdrill->condition->range('{"status":"active"}');
$pivotdrill->status->range('published');
$pivotdrill->account->range('admin');
$pivotdrill->type->range('manual');
$pivotdrill->gen(7);

zenData('user')->gen(1);

su('admin');

$pivotTest = new pivotZenTest();

r($pivotTest->getDrillTest(1, '1', 'status', 'published')) && p('field') && e('status');
r($pivotTest->getDrillTest(2, '1', 'priority', 'published')) && p('field') && e('priority');
r($pivotTest->getDrillTest(3, '1', 'severity', 'published')) && p('field') && e('severity');
r($pivotTest->getDrillTest(4, '1', 'type', 'published')) && p('field') && e('type');
r($pivotTest->getDrillTest(5, '1', 'openedBy', 'published')) && p('field') && e('openedBy');
r($pivotTest->getDrillTest(6, '1', 'product', 'published')) && p('field') && e('product');
r($pivotTest->getDrillTest(7, '1', 'status', 'published')) && p('field') && e('status');
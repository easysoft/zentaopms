#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseAfterBatchCreate();
timeout=0
cid=0

- 执行bugTest模块的responseAfterBatchCreateTest方法，参数是1, '', 0, array 属性result @success
- 执行bugTest模块的responseAfterBatchCreateTest方法，参数是1, '', 0, array 
 - 属性result @success
 - 属性message @保存成功
- 执行bugTest模块的responseAfterBatchCreateTest方法，参数是1, '', 1, array 属性result @success
- 执行bugTest模块的responseAfterBatchCreateTest方法，参数是1, '', 0, array 
 - 属性result @success
 - 属性closeModal @1
- 执行bugTest模块的responseAfterBatchCreateTest方法，参数是1, '', 0, array 
 - 属性result @success
 - 属性message @Custom message

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

su('admin');

$bugTest = new bugTest();

r($bugTest->responseAfterBatchCreateTest(1, '', 0, array(1, 2, 3), '')) && p('result') && e('success');
r($bugTest->responseAfterBatchCreateTest(1, '', 0, array(1, 2, 3), '', 'json')) && p('result,message') && e('success,保存成功');
r($bugTest->responseAfterBatchCreateTest(1, '', 1, array(1, 2, 3), '', 'modal')) && p('result') && e('success');
r($bugTest->responseAfterBatchCreateTest(1, '', 0, array(1, 2, 3), '', 'modal')) && p('result,closeModal') && e('success,1');
r($bugTest->responseAfterBatchCreateTest(1, '', 0, array(1, 2, 3), 'Custom message')) && p('result,message') && e('success,Custom message');
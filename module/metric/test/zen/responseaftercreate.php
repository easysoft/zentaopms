#!/usr/bin/env php
<?php

/**

title=测试 metricZen::responseAfterCreate();
timeout=0
cid=17206

- 执行metricZenTest模块的responseAfterCreateZenTest方法，参数是1, 'back', 'metric', 'http://example.com' 
 - 属性result @success
 - 属性closeModal @1
 - 属性load @http://example.com
- 执行metricZenTest模块的responseAfterCreateZenTest方法，参数是1, 'continue', 'metric', '' 属性result @success
- 执行metricZenTest模块的responseAfterCreateZenTest方法，参数是1, 'back', 'metric', '' 属性result @success
- 执行metricZenTest模块的responseAfterCreateZenTest方法，参数是2, '', 'metriclib', '' 属性result @success
- 执行metricZenTest模块的responseAfterCreateZenTest方法，参数是0, 'continue', 'metric', 'test' 属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

su('admin');

$metricZenTest = new metricZenTest();

r($metricZenTest->responseAfterCreateZenTest(1, 'back', 'metric', 'http://example.com')) && p('result,closeModal,load') && e('success,1,http://example.com');
r($metricZenTest->responseAfterCreateZenTest(1, 'continue', 'metric', '')) && p('result') && e('success');
r($metricZenTest->responseAfterCreateZenTest(1, 'back', 'metric', '')) && p('result') && e('success');
r($metricZenTest->responseAfterCreateZenTest(2, '', 'metriclib', '')) && p('result') && e('success');
r($metricZenTest->responseAfterCreateZenTest(0, 'continue', 'metric', 'test')) && p('result') && e('success');
#!/usr/bin/env php
<?php

/**

title=测试 metricZen::responseAfterEdit();
timeout=0
cid=17207

- 执行metricZenTest模块的responseAfterEditZenTest方法，参数是1, 'back', 'http://example.com' 
 - 属性result @success
 - 属性closeModal @1
 - 属性load @http://example.com
- 执行metricZenTest模块的responseAfterEditZenTest方法，参数是1, 'continue', '' 属性result @success
- 执行metricZenTest模块的responseAfterEditZenTest方法，参数是1, 'back', '' 属性result @success
- 执行metricZenTest模块的responseAfterEditZenTest方法，参数是2, '', '' 属性result @success
- 执行metricZenTest模块的responseAfterEditZenTest方法，参数是0, 'continue', 'test' 属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

su('admin');

$metricZenTest = new metricZenTest();

r($metricZenTest->responseAfterEditZenTest(1, 'back', 'http://example.com')) && p('result,closeModal,load') && e('success,1,http://example.com');
r($metricZenTest->responseAfterEditZenTest(1, 'continue', '')) && p('result') && e('success');
r($metricZenTest->responseAfterEditZenTest(1, 'back', '')) && p('result') && e('success');
r($metricZenTest->responseAfterEditZenTest(2, '', '')) && p('result') && e('success');
r($metricZenTest->responseAfterEditZenTest(0, 'continue', 'test')) && p('result') && e('success');
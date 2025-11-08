#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterAddTemplateType();
timeout=0
cid=0

- 测试正常scope=0的情况属性result @success
- 测试正常scope=1的情况属性result @success
- 测试正常scope=100的情况属性result @success
- 测试负数scope=-1的情况属性result @success
- 测试大数值scope=999999的情况属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->responseAfterAddTemplateTypeTest(0))      && p('result') && e('success'); // 测试正常scope=0的情况
r($docTest->responseAfterAddTemplateTypeTest(1))      && p('result') && e('success'); // 测试正常scope=1的情况
r($docTest->responseAfterAddTemplateTypeTest(100))    && p('result') && e('success'); // 测试正常scope=100的情况
r($docTest->responseAfterAddTemplateTypeTest(-1))     && p('result') && e('success'); // 测试负数scope=-1的情况
r($docTest->responseAfterAddTemplateTypeTest(999999)) && p('result') && e('success'); // 测试大数值scope=999999的情况
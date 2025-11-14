#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseAfterBatchEdit();
timeout=0
cid=17601

- 测试在program tab下,programID为1时的跳转链接属性load @`m=program&f=product&programID=1`
- 测试在program tab下,programID为0时的跳转链接属性load @`m=program&f=productView`
- 测试在product tab下,programID为1时的跳转链接属性load @`m=product&f=all`
- 测试在product tab下,programID为0时的跳转链接属性load @`m=product&f=all`
- 测试返回结果的result字段属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->responseAfterBatchEditTest(1, 'program')) && p('load') && e('`m=program&f=product&programID=1`'); // 测试在program tab下,programID为1时的跳转链接
r($productTest->responseAfterBatchEditTest(0, 'program')) && p('load') && e('`m=program&f=productView`'); // 测试在program tab下,programID为0时的跳转链接
r($productTest->responseAfterBatchEditTest(1, 'product')) && p('load') && e('`m=product&f=all`'); // 测试在product tab下,programID为1时的跳转链接
r($productTest->responseAfterBatchEditTest(0, 'product')) && p('load') && e('`m=product&f=all`'); // 测试在product tab下,programID为0时的跳转链接
r($productTest->responseAfterBatchEditTest(1, 'program')) && p('result') && e('success'); // 测试返回结果的result字段
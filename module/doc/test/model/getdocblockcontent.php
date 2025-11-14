#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocBlockContent();
timeout=0
cid=16075

- 测试1：存在的文档块返回解析后的JSON数组属性title @测试文档块
- 测试2：不存在的文档块ID @0
- 测试3：无效ID（0） @0
- 测试4：包含数组数据的文档块第data条的0属性 @1
- 测试5：空内容的文档块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

su('admin');

$docTest = new docTest();

r($docTest->getDocBlockContentTest(1)) && p('title') && e('测试文档块'); // 测试1：存在的文档块返回解析后的JSON数组
r($docTest->getDocBlockContentTest(999)) && p() && e('0'); // 测试2：不存在的文档块ID
r($docTest->getDocBlockContentTest(0)) && p() && e('0'); // 测试3：无效ID（0）
r($docTest->getDocBlockContentTest(2)) && p('data:0') && e('1'); // 测试4：包含数组数据的文档块
r($docTest->getDocBlockContentTest(4)) && p() && e('0'); // 测试5：空内容的文档块
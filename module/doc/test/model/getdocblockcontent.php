#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocBlockContent();
cid=0

- 测试1：存在的文档块返回解析后的JSON数组 >> 期望返回包含title的数组
- 测试2：不存在的文档块ID >> 期望返回false
- 测试3：无效ID（0） >> 期望返回false
- 测试4：包含数组数据的文档块 >> 期望返回包含data数组的内容
- 测试5：空内容的文档块 >> 期望返回false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

su('admin');

$docTest = new docTest();

r($docTest->getDocBlockContentTest(1)) && p('title') && e('测试文档块'); // 测试1：存在的文档块返回解析后的JSON数组
r($docTest->getDocBlockContentTest(999)) && p() && e(false); // 测试2：不存在的文档块ID
r($docTest->getDocBlockContentTest(0)) && p() && e(false); // 测试3：无效ID（0）
r($docTest->getDocBlockContentTest(2)) && p('data,0') && e(1); // 测试4：包含数组数据的文档块
r($docTest->getDocBlockContentTest(4)) && p() && e(false); // 测试5：空内容的文档块

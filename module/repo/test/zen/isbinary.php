#!/usr/bin/env php
<?php

/**

title=测试 repoZen::isBinary();
timeout=0
cid=18148

- 执行repoZenTest模块的isBinaryTest方法，参数是'This is a normal text content with some words.', ''  @0
- 执行repoZenTest模块的isBinaryTest方法，参数是'Some content', 'pdf'  @1
- 执行repoZenTest模块的isBinaryTest方法，参数是"This content has null byte\x00 in it", ''  @1
- 执行repoZenTest模块的isBinaryTest方法，参数是$highCarriageReturnContent, ''  @1
- 执行repoZenTest模块的isBinaryTest方法，参数是$highSpecialCharContent, ''  @0
- 执行repoZenTest模块的isBinaryTest方法，参数是'', ''  @0
- 执行repoZenTest模块的isBinaryTest方法，参数是'Some image content', 'jpg'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

// 测试正常文本内容
r($repoZenTest->isBinaryTest('This is a normal text content with some words.', '')) && p() && e('0');

// 测试PDF文件后缀
r($repoZenTest->isBinaryTest('Some content', 'pdf')) && p() && e('1');

// 测试包含null字节的二进制内容
r($repoZenTest->isBinaryTest("This content has null byte\x00 in it", '')) && p() && e('1');

// 测试高频率回车换行的内容（频率超过30%）
$highCarriageReturnContent = str_repeat("^\r\n", 200);
r($repoZenTest->isBinaryTest($highCarriageReturnContent, '')) && p() && e('1');

// 测试包含特殊字符模式的内容（无法轻易构造超过30%比例的特殊字符）
$highSpecialCharContent = str_repeat("^ -~", 128);
r($repoZenTest->isBinaryTest($highSpecialCharContent, '')) && p() && e('0');

// 测试空字符串
r($repoZenTest->isBinaryTest('', '')) && p() && e('0');

// 测试其他已知二进制文件后缀（jpg不在binary配置中）
r($repoZenTest->isBinaryTest('Some image content', 'jpg')) && p() && e('0');
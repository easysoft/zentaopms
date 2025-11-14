#!/usr/bin/env php
<?php

/**

title=测试 searchTao::unify();
timeout=0
cid=18347

- 测试将多种特殊符号统一替换为逗号 @hello,world,test,data

- 测试将特殊符号替换为空格 @hello world test data
- 测试将多个连续符号合并为一个 @hello,world

- 测试去除首尾的目标符号 @hello,world,test

- 测试包含中文标点的字符串替换 @你好,世界,测试

- 测试空字符串输入 @0
- 测试仅包含特殊符号的字符串 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$searchTest = new searchTaoTest();

r($searchTest->unifyTest('hello_world、test-data')) && p() && e('hello,world,test,data'); // 测试将多种特殊符号统一替换为逗号
r($searchTest->unifyTest('hello_world、test-data', ' ')) && p() && e('hello world test data'); // 测试将特殊符号替换为空格
r($searchTest->unifyTest('hello___world', ',')) && p() && e('hello,world'); // 测试将多个连续符号合并为一个
r($searchTest->unifyTest('_hello、world-test_', ',')) && p() && e('hello,world,test'); // 测试去除首尾的目标符号
r($searchTest->unifyTest('你好、世界。测试')) && p() && e('你好,世界,测试'); // 测试包含中文标点的字符串替换
r($searchTest->unifyTest('')) && p() && e('0'); // 测试空字符串输入
r($searchTest->unifyTest('_、-。，', ',')) && p() && e('0'); // 测试仅包含特殊符号的字符串
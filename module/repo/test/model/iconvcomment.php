#!/usr/bin/env php
<?php

/**

title=测试 repoModel::iconvComment();
timeout=0
cid=18084

- 测试步骤1：空编码参数情况 @test comment
- 测试步骤2：UTF-8编码转换情况 @utf-8 test
- 测试步骤3：GBK编码转换有效字符串 @测试中文
- 测试步骤4：多编码列表转换情况 @multi test
- 测试步骤5：无效编码参数情况 @invalid test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repo = new repoTest();

r($repo->iconvCommentTest('test comment', '')) && p() && e('test comment'); // 测试步骤1：空编码参数情况
r($repo->iconvCommentTest('utf-8 test', 'utf-8')) && p() && e('utf-8 test'); // 测试步骤2：UTF-8编码转换情况
r($repo->iconvCommentTest('测试中文', 'GBK')) && p() && e('测试中文'); // 测试步骤3：GBK编码转换有效字符串
r($repo->iconvCommentTest('multi test', 'iso-8859-1,GBK,GB2312')) && p() && e('multi test'); // 测试步骤4：多编码列表转换情况
r($repo->iconvCommentTest('invalid test', 'invalid-encoding')) && p() && e('invalid test'); // 测试步骤5：无效编码参数情况
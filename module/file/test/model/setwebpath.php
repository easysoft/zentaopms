#!/usr/bin/env php
<?php

/**

title=测试 fileModel::setWebPath();
timeout=0
cid=16536

- 测试默认公司ID（1）的webPath设置 @/data/upload/1/
- 测试自定义公司ID（5）的webPath设置 @/data/upload/5/
- 测试公司ID为0的边界情况 @/data/upload/0/
- 测试公司ID未设置时的默认值 @/data/upload/1/
- 测试webRoot为空字符串的情况 @data/upload/1/

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/file.unittest.class.php';
su('admin');

$file = new fileTest();

r($file->setWebPathTest()) && p() && e('/data/upload/1/'); // 测试默认公司ID（1）的webPath设置
r($file->setWebPathTest(5)) && p() && e('/data/upload/5/'); // 测试自定义公司ID（5）的webPath设置
r($file->setWebPathTest(0)) && p() && e('/data/upload/0/'); // 测试公司ID为0的边界情况
r($file->setWebPathTest(null)) && p() && e('/data/upload/1/'); // 测试公司ID未设置时的默认值
r($file->setWebPathTest(1, '')) && p() && e('data/upload/1/'); // 测试webRoot为空字符串的情况
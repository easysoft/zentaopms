#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->decodePath();
timeout=0
cid=1

- 测试正常解码 @extention
- 测试数字解码 @5
- 测试多级路径解码 @module/repo/js
- 测试文件路径解码 @abc.php
- 测试空字符串解码 @0

*/

$repo = new repoTest();

$encodedPaths = array('ZXh0ZW50aW9u', 'NQ==', 'bW9kdWxlJTJGcmVwbyUyRmpz', 'YWJjLnBocA==');

r($repo->decodePathTest($encodedPaths[0])) && p() && e('extention'); //测试正常解码
r($repo->decodePathTest($encodedPaths[1])) && p() && e('5'); //测试数字解码
r($repo->decodePathTest($encodedPaths[2])) && p() && e('module/repo/js'); //测试多级路径解码
r($repo->decodePathTest($encodedPaths[3])) && p() && e('abc.php'); //测试文件路径解码
r($repo->decodePathTest(''))               && p() && e('0'); //测试空字符串解码
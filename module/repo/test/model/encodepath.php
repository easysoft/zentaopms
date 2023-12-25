#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->encodePath();
timeout=0
cid=1

- 测试正常路径 @ZXh0ZW50aW9u
- 测试数字路径 @NQ==
- 测试多级路径 @bW9kdWxlJTJGcmVwbyUyRmpz
- 测试文件路径 @YWJjLnBocA==
- 测试空 @0

*/

$repo = new repoTest();

$paths = array('extention', '5', 'module/repo/js', 'abc.php');

r($repo->encodePathTest($paths[0])) && p() && e('ZXh0ZW50aW9u'); //测试正常路径
r($repo->encodePathTest($paths[1])) && p() && e('NQ==');         //测试数字路径
r($repo->encodePathTest($paths[2])) && p() && e('bW9kdWxlJTJGcmVwbyUyRmpz'); //测试多级路径
r($repo->encodePathTest($paths[3])) && p() && e('YWJjLnBocA=='); //测试文件路径
r($repo->encodePathTest(''))        && p() && e('0');            //测试空
#!/usr/bin/env php
<?php

/**

title=svnModel->cat();
timeout=0
cid=1

- 正确的版本号，错误的URL @0
- 正确的版本号，正确的URL @单元测试使用
- 错误的版本号，正确的URL @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');

$failUrl     = 'http://10.0.7.237/svn/repo/unit';
$realVersion = 2;
r($svn->cat($failUrl, $realVersion)) && p() && e('0'); // 正确的版本号，错误的URL

$realUrl = 'https://svn.qc.oop.cc/svn/unittest/README';
r($svn->cat($realUrl, $realVersion)) && p() && e("单元测试使用"); // 正确的版本号，正确的URL

$failVersion = 999;
r($svn->cat($realUrl, $failVersion)) && p() && e('~~'); // 错误的版本号，正确的URL
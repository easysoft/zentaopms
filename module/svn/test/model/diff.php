#!/usr/bin/env php
<?php

/**

title=svnModel->diff();
timeout=0
cid=1

- 正确的版本号，正确的URL @Index: README
- 错误的版本号，正确的URL @===================================================================
- 正确的版本号，错误的URL @Cannot display: file marked as a binary type.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');

$realUrl     = 'https://svn.qc.oop.cc/svn/unittest';
$realVersion = 1;
r($svn->diff($realUrl, $realVersion)) && p() && e('Index: README'); // 正确的版本号，正确的URL

$failVersion = 999;
r($svn->diff($realUrl, $failVersion)) && p() && e('==================================================================='); // 错误的版本号，正确的URL

$failUrl = 'http://fail.url';
r($svn->diff($failUrl, $realVersion)) && p() && e('Cannot display: file marked as a binary type.'); // 正确的版本号，错误的URL
#!/usr/bin/env php
<?php

/**

title=svnModel->setClient();
timeout=0
cid=1

- 测试https开头没有用户名密码的svn地址 @/usr/bin/svn --non-interactive --trust-server-cert
- 测试http开头没有用户名密码的svn地址 @/usr/bin/svn --non-interactive
- 测试svn开头有用户名密码的svn地址 @/usr/bin/svn --non-interactive --trust-server-cert --username test --password test --no-auth-cache

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester;
$svn = $tester->loadModel('svn');

$repo = new stdclass();
$repo->client = 'svn';
$repo->path   = 'https://https-test';
$svn->setClient($repo);
r($svn->client) && p() && e('svn --non-interactive --trust-server-cert'); // 测试https开头没有用户名密码的svn地址

$repo->path = 'http://https-test';
$svn->setClient($repo);
r($svn->client) && p() && e('svn --non-interactive'); // 测试http开头没有用户名密码的svn地址

$repo->path     = 'svn://https-test';
$repo->account  = 'test';
$repo->password = 'test';
$svn->setClient($repo);
r($svn->client) && p() && e('svn --non-interactive --trust-server-cert --username test --password test --no-auth-cache'); // 测试svn开头有用户名密码的svn地址

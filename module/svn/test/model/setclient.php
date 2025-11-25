#!/usr/bin/env php
<?php

/**

title=测试 svnModel::setClient();
timeout=0
cid=18722

- 执行svnTest模块的setClientTest方法，参数是$repo 属性client @svn --non-interactive
- 执行svnTest模块的setClientTest方法，参数是$repo 属性client @svn --non-interactive
- 执行svnTest模块的setClientTest方法，参数是$repo 属性client @svn --non-interactive
- 执行svnTest模块的setClientTest方法，参数是$repo 属性client @svn --non-interactive --username testuser --password testpass --no-auth-cache
- 执行svnTest模块的setClientTest方法，参数是$repo 属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

su('admin');

$svnTest = new svnTest();

// 步骤1：测试HTTPS协议且无用户名密码的情况（SVN客户端不存在时返回false）
$repo = new stdclass();
$repo->client = 'svn';
$repo->path   = 'https://example.com/svn/repo';
r($svnTest->setClientTest($repo)) && p('client') && e('svn --non-interactive');

// 步骤2：测试HTTP协议且无用户名密码的情况（HTTP不需要版本检查）
$repo = new stdclass();
$repo->client = 'svn';
$repo->path   = 'http://example.com/svn/repo';
r($svnTest->setClientTest($repo)) && p('client') && e('svn --non-interactive');

// 步骤3：测试SVN协议且有用户名密码的情况（SVN客户端不存在时返回false）
$repo = new stdclass();
$repo->client   = 'svn';
$repo->path     = 'svn://example.com/svn/repo';
$repo->account  = 'testuser';
$repo->password = 'testpass';
r($svnTest->setClientTest($repo)) && p('client') && e('svn --non-interactive');

// 步骤4：测试HTTP协议且有用户名密码的情况
$repo = new stdclass();
$repo->client   = 'svn';
$repo->path     = 'http://example.com/svn/repo';
$repo->account  = 'testuser';
$repo->password = 'testpass';
r($svnTest->setClientTest($repo)) && p('client') && e('svn --non-interactive --username testuser --password testpass --no-auth-cache');

// 步骤5：测试HTTP协议方法返回值验证
$repo = new stdclass();
$repo->client = 'svn';
$repo->path   = 'http://example.com/svn/repo';
r($svnTest->setClientTest($repo)) && p('result') && e('1');
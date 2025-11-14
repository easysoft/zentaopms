#!/usr/bin/env php
<?php

/**

title=测试 svnModel::setRepo();
timeout=0
cid=18723

- 步骤1：正常SVN仓库设置属性result @1
- 步骤2：HTTPS协议SVN仓库设置属性result @1
- 步骤3：本地文件协议仓库设置属性result @1
- 步骤4：带认证信息的仓库设置属性result @1
- 步骤5：空路径参数测试属性result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

$table = zenData('repo');
$table->loadYaml('repo_setrepo', false, 2)->gen(5);

su('admin');

$svnTest = new svnTest();

// 准备测试仓库对象
$normalRepo = new stdClass();
$normalRepo->id = 1;
$normalRepo->name = 'svnrepo1';
$normalRepo->path = 'https://svn.qc.oop.cc/svn/unittest';
$normalRepo->SCM = 'Subversion';
$normalRepo->client = 'svn';
$normalRepo->account = 'admin';
$normalRepo->password = 'KXdOi8zgTcUqEFX2Hx8B';
$normalRepo->encoding = 'utf-8';

$httpsRepo = new stdClass();
$httpsRepo->id = 2;
$httpsRepo->name = 'testsvn2';
$httpsRepo->path = 'https://secure.svn.server/repo';
$httpsRepo->SCM = 'Subversion';
$httpsRepo->client = 'svn';
$httpsRepo->account = 'testuser';
$httpsRepo->password = 'testpass';
$httpsRepo->encoding = 'utf-8';

$fileRepo = new stdClass();
$fileRepo->id = 3;
$fileRepo->name = 'emptyrepo3';
$fileRepo->path = 'file:///home/test/svn';
$fileRepo->SCM = 'Subversion';
$fileRepo->client = 'svn';
$fileRepo->account = '';
$fileRepo->password = '';
$fileRepo->encoding = 'utf-8';

$svnProtocolRepo = new stdClass();
$svnProtocolRepo->id = 4;
$svnProtocolRepo->name = 'httpsrepo4';
$svnProtocolRepo->path = 'svn://test.server/repo';
$svnProtocolRepo->SCM = 'Subversion';
$svnProtocolRepo->client = 'svn';
$svnProtocolRepo->account = 'guest';
$svnProtocolRepo->password = 'emptypass';
$svnProtocolRepo->encoding = 'utf-8';

$emptyRepo = new stdClass();
$emptyRepo->id = 5;
$emptyRepo->name = 'invalidrepo5';
$emptyRepo->path = '';
$emptyRepo->SCM = 'Subversion';
$emptyRepo->client = 'svn';
$emptyRepo->account = '';
$emptyRepo->password = '';
$emptyRepo->encoding = 'utf-8';

r($svnTest->setRepoTest($normalRepo)) && p('result') && e('1'); // 步骤1：正常SVN仓库设置
r($svnTest->setRepoTest($httpsRepo)) && p('result') && e('1'); // 步骤2：HTTPS协议SVN仓库设置
r($svnTest->setRepoTest($fileRepo)) && p('result') && e('1'); // 步骤3：本地文件协议仓库设置
r($svnTest->setRepoTest($svnProtocolRepo)) && p('result') && e('1'); // 步骤4：带认证信息的仓库设置
r($svnTest->setRepoTest($emptyRepo)) && p('result') && e('1'); // 步骤5：空路径参数测试
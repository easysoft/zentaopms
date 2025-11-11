#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getFilesInfo();
timeout=0
cid=0

- 步骤1:正常Git仓库返回2个文件信息 @2
- 步骤2:Gitlab仓库revision字段为空第0条的revision属性 @~~
- 步骤3:SVN仓库返回数字revision第0条的revision属性 @1
- 步骤4:空repo对象返回空数组 @0
- 步骤5:Git仓库revision被截取为10位 @10
- 步骤6:Gitlab文件comment为空第0条的comment属性 @~~
- 步骤7:SVN仓库返回文件类型第0条的kind属性 @file

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoTest = new repoZenTest();

// 测试步骤1: 测试Git仓库根路径文件列表
$gitRepo = new stdClass();
$gitRepo->id = 1;
$gitRepo->SCM = 'Git';
$gitRepo->name = 'TestGitRepo';
r(count($repoTest->getFilesInfoTest($gitRepo, '', 'master', base64_encode('master'), 1))) && p() && e('2'); // 步骤1:正常Git仓库返回2个文件信息

// 测试步骤2: 测试Gitlab仓库根路径文件列表
$gitlabRepo = new stdClass();
$gitlabRepo->id = 2;
$gitlabRepo->SCM = 'Gitlab';
$gitlabRepo->name = 'TestGitlabRepo';
r($repoTest->getFilesInfoTest($gitlabRepo, '', 'master', base64_encode('master'), 1)) && p('0:revision') && e('~~'); // 步骤2:Gitlab仓库revision字段为空

// 测试步骤3: 测试Subversion仓库根路径文件列表
$svnRepo = new stdClass();
$svnRepo->id = 3;
$svnRepo->SCM = 'Subversion';
$svnRepo->name = 'TestSvnRepo';
r($repoTest->getFilesInfoTest($svnRepo, '', '', '', 1)) && p('0:revision') && e('1'); // 步骤3:SVN仓库返回数字revision

// 测试步骤4: 测试空repo对象
r($repoTest->getFilesInfoTest(null, '', '', '', 1)) && p() && e('0'); // 步骤4:空repo对象返回空数组

// 测试步骤5: 测试Git仓库目录文件revision截取
$gitRepo2 = new stdClass();
$gitRepo2->id = 4;
$gitRepo2->SCM = 'Git';
$gitRepo2->name = 'TestGitRepo2';
r(strlen($repoTest->getFilesInfoTest($gitRepo2, 'src', 'develop', base64_encode('develop'), 1)[0]->revision)) && p() && e('10'); // 步骤5:Git仓库revision被截取为10位

// 测试步骤6: 测试Gitlab仓库文件comment为空
$gitlabRepo2 = new stdClass();
$gitlabRepo2->id = 5;
$gitlabRepo2->SCM = 'Gitlab';
$gitlabRepo2->name = 'TestGitlabRepo2';
r($repoTest->getFilesInfoTest($gitlabRepo2, 'docs', 'main', base64_encode('main'), 1)) && p('0:comment') && e('~~'); // 步骤6:Gitlab文件comment为空

// 测试步骤7: 测试Subversion仓库文件路径处理
$svnRepo2 = new stdClass();
$svnRepo2->id = 6;
$svnRepo2->SCM = 'Subversion';
$svnRepo2->name = 'TestSvnRepo2';
r($repoTest->getFilesInfoTest($svnRepo2, 'trunk', '', '', 1)) && p('0:kind') && e('file'); // 步骤7:SVN仓库返回文件类型
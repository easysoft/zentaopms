#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getBranchAndTagItems();
timeout=0
cid=18135

- 步骤1:选中master分支属性selected @master
- 步骤2:选中v1.0标签属性selected @v1.0
- 步骤3:选中develop分支属性selected @develop
- 步骤4:选中feature/test分支属性selected @feature/test
- 步骤5:测试非Git类型仓库 @0
- 步骤6:测试空repo对象 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoTest = new repoZenTest();

// 创建测试用的repo对象
$gitRepo = new stdClass();
$gitRepo->id = 1;
$gitRepo->name = 'test-git-repo';
$gitRepo->SCM = 'Git';
$gitRepo->path = '/tmp/test-repo';
$gitRepo->encoding = 'utf-8';
$gitRepo->client = 'git';

$svnRepo = new stdClass();
$svnRepo->id = 2;
$svnRepo->name = 'test-svn-repo';
$svnRepo->SCM = 'Subversion';
$svnRepo->path = '/tmp/test-svn';
$svnRepo->encoding = 'utf-8';
$svnRepo->client = 'svn';

r($repoTest->getBranchAndTagItemsTest($gitRepo, 'master')) && p('selected') && e('master'); // 步骤1:选中master分支
r($repoTest->getBranchAndTagItemsTest($gitRepo, 'v1.0')) && p('selected') && e('v1.0'); // 步骤2:选中v1.0标签
r($repoTest->getBranchAndTagItemsTest($gitRepo, 'develop')) && p('selected') && e('develop'); // 步骤3:选中develop分支
r($repoTest->getBranchAndTagItemsTest($gitRepo, 'feature/test')) && p('selected') && e('feature/test'); // 步骤4:选中feature/test分支
r($repoTest->getBranchAndTagItemsTest($svnRepo, '')) && p() && e('0'); // 步骤5:测试非Git类型仓库
r($repoTest->getBranchAndTagItemsTest(null, '')) && p() && e('0'); // 步骤6:测试空repo对象
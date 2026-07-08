#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->isSvn();
timeout=0
cid=0

- scmType=svn 返 1 @1
- scmType=SVN 大小写不敏感 返 1 @1
- scmType=git 返 0 @0
- scmType 字段缺失返 0 @0
- scmType 为空串返 0 @0

*/

su('admin');

$repoTest = new repoModelTest();

$svnRepo = new stdclass();
$svnRepo->scmType = 'svn';
r($repoTest->isSvnTest($svnRepo)) && p() && e('1'); // scmType=svn 返 1

$upperSvnRepo = new stdclass();
$upperSvnRepo->scmType = 'SVN';
r($repoTest->isSvnTest($upperSvnRepo)) && p() && e('1'); // 大小写不敏感

$gitRepo = new stdclass();
$gitRepo->scmType = 'git';
r($repoTest->isSvnTest($gitRepo)) && p() && e('0'); // scmType=git 返 0

$noFieldRepo = new stdclass();
r($repoTest->isSvnTest($noFieldRepo)) && p() && e('0'); // scmType 字段缺失

$emptyScmRepo = new stdclass();
$emptyScmRepo->scmType = '';
r($repoTest->isSvnTest($emptyScmRepo)) && p() && e('0'); // scmType 为空串

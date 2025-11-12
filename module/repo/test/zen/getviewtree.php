#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getViewTree();
timeout=0
cid=0

- 测试步骤1:空repo对象 @0
- 测试步骤2:Gitlab类型repo @2
- 测试步骤3:Git类型repo @2
- 测试步骤4:Subversion类型repo @2
- 测试步骤5:Subversion类型repo验证目录kind属性第1条的kind属性 @dir

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

zendata('repo')->gen(0);

su('admin');

$repoTest = new repoZenTest();

// 测试步骤1:空repo对象
$emptyRepo = null;
$entry = '';
$revision = '';

// 测试步骤2:Gitlab类型repo
$gitlabRepo = new stdClass();
$gitlabRepo->id = 1;
$gitlabRepo->SCM = 'Gitlab';
$gitlabRepo->name = 'test-gitlab';

// 测试步骤3:Git类型repo
$gitRepo = new stdClass();
$gitRepo->id = 2;
$gitRepo->SCM = 'Git';
$gitRepo->name = 'test-git';

// 测试步骤4:Subversion类型repo
$svnRepo = new stdClass();
$svnRepo->id = 3;
$svnRepo->SCM = 'Subversion';
$svnRepo->name = 'test-svn';

r($repoTest->getViewTreeTest($emptyRepo, $entry, $revision)) && p() && e('0'); // 测试步骤1:空repo对象
r(count($repoTest->getViewTreeTest($gitlabRepo, $entry, $revision))) && p() && e('2'); // 测试步骤2:Gitlab类型repo
r(count($repoTest->getViewTreeTest($gitRepo, $entry, $revision))) && p() && e('2'); // 测试步骤3:Git类型repo
r(count($repoTest->getViewTreeTest($svnRepo, '/trunk', 'HEAD'))) && p() && e('2'); // 测试步骤4:Subversion类型repo
r($repoTest->getViewTreeTest($svnRepo, '/trunk', 'HEAD')) && p('1:kind') && e('dir'); // 测试步骤5:Subversion类型repo验证目录kind属性
#!/usr/bin/env php
<?php

/**

title=测试 gitModel::getRepoTags();
timeout=0
cid=16548

- 测试步骤1：使用现有仓库获取标签列表 @tag1
- 测试步骤2：使用缺少client属性的仓库对象 @0
- 测试步骤3：使用缺少path属性的仓库对象 @0
- 测试步骤4：使用缺少account属性的仓库对象 @0
- 测试步骤5：使用空的仓库对象测试边界情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

zenData('repo')->loadYaml('repo')->gen(1);
su('admin');

$gitTest = new gitTest();

global $tester;
$git = $tester->loadModel('git');
$git->setRepos();
$realRepo = $git->repos[1];

// 准备无效测试数据
$noClientRepo = new stdclass();
$noClientRepo->path     = '/test/repo';
$noClientRepo->account  = 'testuser';
$noClientRepo->password = 'testpass';
$noClientRepo->encoding = 'utf-8';
$noClientRepo->SCM      = 'Git';

$noPathRepo = new stdclass();
$noPathRepo->client   = 'git';
$noPathRepo->account  = 'testuser';
$noPathRepo->password = 'testpass';
$noPathRepo->encoding = 'utf-8';
$noPathRepo->SCM      = 'Git';

$noAccountRepo = new stdclass();
$noAccountRepo->client   = 'git';
$noAccountRepo->path     = '/test/repo';
$noAccountRepo->password = 'testpass';
$noAccountRepo->encoding = 'utf-8';
$noAccountRepo->SCM      = 'Git';

$emptyRepo = new stdclass();

r($gitTest->getRepoTagsTest($realRepo)) && p('0') && e('tag1'); // 测试步骤1：使用现有仓库获取标签列表
r($gitTest->getRepoTagsTest($noClientRepo)) && p() && e('0'); // 测试步骤2：使用缺少client属性的仓库对象
r($gitTest->getRepoTagsTest($noPathRepo)) && p() && e('0'); // 测试步骤3：使用缺少path属性的仓库对象
r($gitTest->getRepoTagsTest($noAccountRepo)) && p() && e('0'); // 测试步骤4：使用缺少account属性的仓库对象
r($gitTest->getRepoTagsTest($emptyRepo)) && p() && e('0'); // 测试步骤5：使用空的仓库对象测试边界情况
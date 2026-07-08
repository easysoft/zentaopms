#!/usr/bin/env php
<?php

/**

title=测试 mrZen::getBranchUrl();
timeout=0
cid=17268

- 执行mrTest模块的getBranchUrlTest方法，参数是$gitlabHost, 100, 'main'  @0
- 执行mrTest模块的getBranchUrlTest方法，参数是$giteaHost, 'test/project', 'develop'  @0
- 执行mrTest模块的getBranchUrlTest方法，参数是$gogsHost, 'user/repo', 'master'  @0
- 执行mrTest模块的getBranchUrlTest方法，参数是$gitlabHost, 200, 'feature-branch'  @0
- 执行mrTest模块的getBranchUrlTest方法，参数是$giteaHost, 'org/project', 'release'  @0
- 执行mrTest模块的getBranchUrlTest方法，参数是$gitlabHost, 100, 'nonexistent-branch'  @0
- 执行mrTest模块的getBranchUrlTest方法，参数是$gitlabHost, 100, ''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('view');

zenData('pipeline')->gen(0);

su('admin');

$mrTest = new mrZenTest();

// 创建不同类型的主机对象
$gitlabHost = new stdclass();
$gitlabHost->id = 1;
$gitlabHost->type = 'gitlab';

$giteaHost = new stdclass();
$giteaHost->id = 2;
$giteaHost->type = 'gitea';

$gogsHost = new stdclass();
$gogsHost->id = 3;
$gogsHost->type = 'gogs';

// 测试步骤1: GitLab主机获取存在的分支URL
r($mrTest->getBranchUrlTest($gitlabHost, 100, 'main')) && p() && e('0');
// 测试步骤2: Gitea主机获取存在的分支URL
r($mrTest->getBranchUrlTest($giteaHost, 'test/project', 'develop')) && p() && e('0');
// 测试步骤3: Gogs主机获取存在的分支URL
r($mrTest->getBranchUrlTest($gogsHost, 'user/repo', 'master')) && p() && e('0');
// 测试步骤4: GitLab主机使用整数项目ID获取分支URL
r($mrTest->getBranchUrlTest($gitlabHost, 200, 'feature-branch')) && p() && e('0');
// 测试步骤5: Gitea主机使用字符串项目ID获取分支URL
r($mrTest->getBranchUrlTest($giteaHost, 'org/project', 'release')) && p() && e('0');
// 测试步骤6: 获取不存在的分支
r($mrTest->getBranchUrlTest($gitlabHost, 100, 'nonexistent-branch')) && p() && e('0');
// 测试步骤7: 使用空分支名称
r($mrTest->getBranchUrlTest($gitlabHost, 100, '')) && p() && e('0');
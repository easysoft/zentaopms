#!/usr/bin/env php
<?php

/**

title=测试 mrZen::getBranchUrl();
timeout=0
cid=0

- 执行mrTest模块的getBranchUrlTest方法，参数是$gitlabHost, 1, 'master'  @https://gitlab.example.com/project/repo/-/tree/master
- 执行mrTest模块的getBranchUrlTest方法，参数是$giteaHost, 'project/repo', 'develop'  @https://gitea.example.com/project/repo/src/branch/develop
- 执行mrTest模块的getBranchUrlTest方法，参数是$gogsHost, 'project/repo', 'feature-test'  @https://gogs.example.com/project/repo/src/feature-test
- 执行mrTest模块的getBranchUrlTest方法，参数是$gitlabHost, 1, 'nonexistent'  @0
- 执行mrTest模块的getBranchUrlTest方法，参数是$invalidHost, 1, 'master'  @unsupported_host_type

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

$mrTest = new mrTest();

// 测试步骤1：正常GitLab分支获取URL
$gitlabHost = new stdClass();
$gitlabHost->type = 'gitlab';
$gitlabHost->id = 1;
r($mrTest->getBranchUrlTest($gitlabHost, 1, 'master')) && p() && e('https://gitlab.example.com/project/repo/-/tree/master');

// 测试步骤2：正常Gitea分支获取URL
$giteaHost = new stdClass();
$giteaHost->type = 'gitea';
$giteaHost->id = 1;
r($mrTest->getBranchUrlTest($giteaHost, 'project/repo', 'develop')) && p() && e('https://gitea.example.com/project/repo/src/branch/develop');

// 测试步骤3：正常Gogs分支获取URL
$gogsHost = new stdClass();
$gogsHost->type = 'gogs';
$gogsHost->id = 1;
r($mrTest->getBranchUrlTest($gogsHost, 'project/repo', 'feature-test')) && p() && e('https://gogs.example.com/project/repo/src/feature-test');

// 测试步骤4：分支不存在情况
r($mrTest->getBranchUrlTest($gitlabHost, 1, 'nonexistent')) && p() && e('0');

// 测试步骤5：无效主机类型
$invalidHost = new stdClass();
$invalidHost->type = 'unknown';
$invalidHost->id = 1;
r($mrTest->getBranchUrlTest($invalidHost, 1, 'master')) && p() && e('unsupported_host_type');
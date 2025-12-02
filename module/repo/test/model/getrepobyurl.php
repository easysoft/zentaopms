#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getRepoByUrl();
timeout=0
cid=18075

- 执行repoTest模块的getRepoByUrlTest方法，参数是'' 属性message @Url is empty.
- 执行repoTest模块的getRepoByUrlTest方法，参数是'http://invalid.com/project.git' 属性message @No matched gitlab.
- 执行repoTest模块的getRepoByUrlTest方法，参数是'https://nonexistent.gitlab.com/repo.git' 属性message @No matched gitlab.
- 执行repoTest模块的getRepoByUrlTest方法，参数是'http://another.invalid.com/test.git' 属性message @No matched gitlab.
- 执行repoTest模块的getRepoByUrlTest方法，参数是'https://fake.gitlab.server.com/project/repo.git' 属性message @No matched gitlab.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 准备最小化测试数据
$pipeline = zenData('pipeline');
$pipeline->id->range('1');
$pipeline->name->range('test-gitlab');
$pipeline->type->range('gitlab');
$pipeline->url->range('http://test.gitlab.com');
$pipeline->deleted->range('0');
$pipeline->gen(1);

$repo = zenData('repo');
$repo->id->range('1');
$repo->SCM->range('Gitlab');
$repo->serviceHost->range('1');
$repo->serviceProject->range('test-project');
$repo->preMerge->range('0');
$repo->job->range('0');
$repo->deleted->range('0');
$repo->gen(1);

// 模拟用户登录
su('admin');

// 创建测试实例
$repoTest = new repoTest();

// 测试步骤1：使用空URL
r($repoTest->getRepoByUrlTest('')) && p('message') && e('Url is empty.');

// 测试步骤2：使用无效URL（不匹配任何GitLab）
r($repoTest->getRepoByUrlTest('http://invalid.com/project.git')) && p('message') && e('No matched gitlab.');

// 测试步骤3：使用另一个无效URL
r($repoTest->getRepoByUrlTest('https://nonexistent.gitlab.com/repo.git')) && p('message') && e('No matched gitlab.');

// 测试步骤4：测试另一个无效但格式正确的URL
r($repoTest->getRepoByUrlTest('http://another.invalid.com/test.git')) && p('message') && e('No matched gitlab.');

// 测试步骤5：测试不存在的GitLab服务器
r($repoTest->getRepoByUrlTest('https://fake.gitlab.server.com/project/repo.git')) && p('message') && e('No matched gitlab.');
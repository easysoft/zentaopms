#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getNotExistRepos();
timeout=0
cid=0

- 执行repoZenTest模块的getNotExistReposTest方法，参数是$server1 第0条的name属性 @project1
- 执行repoZenTest模块的getNotExistReposTest方法，参数是$server2  @0
- 执行repoZenTest模块的getNotExistReposTest方法，参数是$server3 第0条的name属性 @newproject1
- 执行repoZenTest模块的getNotExistReposTest方法，参数是$server4  @0
- 执行repoZenTest模块的getNotExistReposTest方法，参数是$server5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

$table = zenData('repo');
$table->id->range('1-10');
$table->serviceHost->range('1{3},2{3},3{4}');
$table->serviceProject->range('1,2,3,4,5,101,102,103,104,105');
$table->SCM->range('Gitlab{10}');
$table->name->range('repo1,repo2,repo3,repo4,repo5,repo-test-1,repo-test-2,repo-test-3,repo-test-4,repo-test-5');
$table->gen(10);

su('admin');

$repoZenTest = new repoZenTest();

// 测试步骤1：有效服务器对象，存在GitLab项目，部分已存在于禅道
$server1 = new stdClass();
$server1->id = 1;
$server1->type = 'gitlab';
$server1->url = 'https://gitlab.example.com';
$server1->token = 'test-token';

r($repoZenTest->getNotExistReposTest($server1)) && p('0:name') && e('project1');

// 测试步骤2：有效服务器对象，GitLab项目全部已存在于禅道
$server2 = new stdClass();
$server2->id = 2;
$server2->type = 'gitlab';
$server2->url = 'https://gitlab.example.com';
$server2->token = 'test-token';

r($repoZenTest->getNotExistReposTest($server2)) && p() && e('0');

// 测试步骤3：有效服务器对象，GitLab项目全部不存在于禅道
$server3 = new stdClass();
$server3->id = 3;
$server3->type = 'gitlab';
$server3->url = 'https://gitlab.example.com';
$server3->token = 'test-token';

r($repoZenTest->getNotExistReposTest($server3)) && p('0:name') && e('newproject1');

// 测试步骤4：空服务器对象
$server4 = null;

r($repoZenTest->getNotExistReposTest($server4)) && p() && e('0');

// 测试步骤5：无效服务器对象，API调用失败
$server5 = new stdClass();
$server5->id = 999;
$server5->type = 'gitlab';
$server5->url = 'https://invalid.gitlab.com';
$server5->token = 'invalid-token';

r($repoZenTest->getNotExistReposTest($server5)) && p() && e('0');
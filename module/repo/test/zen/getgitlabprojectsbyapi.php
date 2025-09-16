#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getGitlabProjectsByApi();
timeout=0
cid=0

- 执行repoZenTest模块的getGitlabProjectsByApiTest方法，参数是'valid_server'  @2
- 执行repoZenTest模块的getGitlabProjectsByApiTest方法，参数是'invalid_token'  @0
- 执行repoZenTest模块的getGitlabProjectsByApiTest方法，参数是'invalid_url'  @0
- 执行repoZenTest模块的getGitlabProjectsByApiTest方法，参数是'empty_server'  @0
- 执行repoZenTest模块的getGitlabProjectsByApiTest方法，参数是'special_id' 第0条的id属性 @123

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_getgitlabprojectsbyapi.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$repoZenTest = new repoZenGetGitlabProjectsByApiTest();

r($repoZenTest->getGitlabProjectsByApiTest('valid_server')) && p() && e('2');
r($repoZenTest->getGitlabProjectsByApiTest('invalid_token')) && p() && e('0');
r($repoZenTest->getGitlabProjectsByApiTest('invalid_url')) && p() && e('0');
r($repoZenTest->getGitlabProjectsByApiTest('empty_server')) && p() && e('0');
r($repoZenTest->getGitlabProjectsByApiTest('special_id')) && p('0:id') && e('123');
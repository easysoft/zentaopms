#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/**

title=测试 providerZen::getCheckHeaders();
timeout=0
cid=0

- 步骤1：GitLab 令牌生成 PRIVATE-TOKEN 请求头 @PRIVATE-TOKEN: gitlab-token
- 步骤2：GitHub 令牌生成 Bearer 请求头 @Authorization: Bearer github-token
- 步骤3：Jenkins 令牌生成 basic 请求头 @Authorization: basic jenkins-token
- 步骤4：Gitea 令牌生成 token 请求头 @Authorization: token gitea-token
- 步骤5：空令牌时不生成请求头 @0

*/

$providerZen = new providerZenTest();

r($providerZen->getCheckHeadersTest('GitLab', 'gitlab-token')) && p('0') && e('PRIVATE-TOKEN: gitlab-token');           // 步骤1：GitLab 令牌生成 PRIVATE-TOKEN 请求头
r($providerZen->getCheckHeadersTest('GitHub', 'github-token')) && p('0') && e('Authorization: Bearer github-token');   // 步骤2：GitHub 令牌生成 Bearer 请求头
r($providerZen->getCheckHeadersTest('Jenkins', 'jenkins-token')) && p('0') && e('Authorization: basic jenkins-token'); // 步骤3：Jenkins 令牌生成 basic 请求头
r($providerZen->getCheckHeadersTest('Gitea', 'gitea-token')) && p('0') && e('Authorization: token gitea-token');       // 步骤4：Gitea 令牌生成 token 请求头
r(count($providerZen->getCheckHeadersTest('GitLab', ''))) && p() && e('0');                                             // 步骤5：空令牌时不生成请求头

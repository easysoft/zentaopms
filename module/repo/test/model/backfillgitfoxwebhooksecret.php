#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::backfillGitFoxWebhookSecret();
timeout=0
cid=0

- 步骤1：回填后返回 true，更新 3 个匹配的 GitFox webhook @1,3
- 步骤2：id=1 的 webhook secret/authMethod/authHeader 被回填 @repo-uid-1,token,X-Gitfox-Token
- 步骤3：id=2 的 webhook secret/authMethod/authHeader 被回填 @repo-uid-1,token,X-Gitfox-Token
- 步骤4：id=3 的 webhook 使用仓库 gitUID 回填 @repo-uid-2,token,X-Gitfox-Token
- 步骤5：gitUID 为 empty_gituid_ 的仓库对应 hook 不更新 @old-secret-4,none,old-header-4
- 步骤6：镜像库对应 hook 不更新 @old-secret-5,none
- 步骤7：已删除 webhook 不更新，重复执行幂等 @old-secret-8,none

*/

zenData('ops_repo')->gen(0);
zenData('ops_webhooks')->gen(0);

zenData('ops_repo')->loadYaml('ops_repo')->gen(5);
zenData('ops_webhooks')->loadYaml('ops_webhooks')->gen(8);

$repoTest = new repoModelTest();

r($repoTest->backfillGitFoxWebhookSecretTest()) && p('result,updatedCount')                    && e('1,3');                       // 步骤1
r($repoTest->backfillGitFoxWebhookSecretTest()) && p('hook1:secret,authMethod,authHeader')     && e('repo-uid-1,token,X-Gitfox-Token'); // 步骤2
r($repoTest->backfillGitFoxWebhookSecretTest()) && p('hook2:secret,authMethod,authHeader')     && e('repo-uid-1,token,X-Gitfox-Token'); // 步骤3
r($repoTest->backfillGitFoxWebhookSecretTest()) && p('hook3:secret,authMethod,authHeader')     && e('repo-uid-2,token,X-Gitfox-Token'); // 步骤4
r($repoTest->backfillGitFoxWebhookSecretTest()) && p('hook4:secret,authMethod,authHeader')     && e('old-secret-4,none,old-header-4'); // 步骤5
r($repoTest->backfillGitFoxWebhookSecretTest()) && p('hook5:secret,authMethod')                && e('old-secret-5,none');        // 步骤6
r($repoTest->backfillGitFoxWebhookSecretTest()) && p('hook8:secret,authMethod')                && e('old-secret-8,none');        // 步骤7

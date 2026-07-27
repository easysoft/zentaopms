#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 providerModel::migratePipelineProviders();
timeout=0
cid=0

- 步骤1：迁移正常返回 true @1
- 步骤2：迁移后 ops_provider 表新增 5 条记录（GitLab/Gitea/Jenkins/Subversion/GitHub） @5
- 步骤3：大小写归一：GITEA 类型迁移为 Gitea @Gitea
- 步骤4：Jenkins 类型的 token 使用 base64(account:token) 编码 @1
- 步骤5：excludeTypes（gitfox/sonarqube）不被迁移 @0
- 步骤6：无映射的 bitbucket 类型被静默跳过 @0
- 步骤7：迁移后 ops_provider 的 id 与原始 pipeline id 一致 @1

*/

// 用 test/data 下的 sql 建/重置目标表与源表，保证 schema 一致并清空历史数据。
global $dbh, $app;
$dataRoot = $app->getAppRoot() . 'test' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
foreach(array('ops_provider.sql', 'pipeline.sql') as $schema)
{
    $schemaFile = $dataRoot . $schema;
    if(file_exists($schemaFile)) $dbh->exec(file_get_contents($schemaFile));
}

// 源表 pipeline 走 base yaml + 方法级 yaml 覆盖。base yaml 已包含合法 createdDate/private/password 等字段。
zenData('pipeline')->loadYaml('pipeline_migratepipelineproviders')->gen(8);

su('admin');

$providerTester = new providerModelTest();
$dao            = $providerTester->instance->dao;

$providerBefore = (int)$dao->select('COUNT(1) AS cnt')->from(TABLE_PROVIDER)->fetch('cnt');

r($providerTester->migratePipelineProvidersTest()) && p() && e('1'); // 步骤1：迁移正常返回 true

$providerAfter = (int)$dao->select('COUNT(1) AS cnt')->from(TABLE_PROVIDER)->fetch('cnt');
r($providerAfter - $providerBefore) && p() && e('5'); // 步骤2：迁移后新增 5 条

$giteaRow = $dao->select('type')->from(TABLE_PROVIDER)->where('name')->eq('Gitea-Upper')->fetch();
r($giteaRow ? $giteaRow->type : '') && p() && e('Gitea'); // 步骤3：大小写归一

$jenkinsRow    = $dao->select('token')->from(TABLE_PROVIDER)->where('name')->eq('Jenkins-B')->fetch();
$expectedToken = base64_encode('ci-user:jenkins-token');
r($jenkinsRow && $jenkinsRow->token === $expectedToken ? '1' : '0') && p() && e('1'); // 步骤4：Jenkins token base64

$excludeCount = (int)$dao->select('COUNT(1) AS cnt')->from(TABLE_PROVIDER)->where('name')->in('GitFox-Skip,Sonar-Skip')->fetch('cnt');
r($excludeCount) && p() && e('0'); // 步骤5：excludeTypes 不迁移

$unknownCount = (int)$dao->select('COUNT(1) AS cnt')->from(TABLE_PROVIDER)->where('name')->eq('BitB-Unknown')->fetch('cnt');
r($unknownCount) && p() && e('0'); // 步骤6：无映射类型跳过

$gitlabProvider = $dao->select('id')->from(TABLE_PROVIDER)->where('name')->eq('GitLab-A')->fetch();
r($gitlabProvider && (int)$gitlabProvider->id === 1 ? '1' : '0') && p() && e('1'); // 步骤7：迁移后保留旧id

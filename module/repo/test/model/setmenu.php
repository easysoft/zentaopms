#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $app;
$app->config->debug = 0;

/**

title=测试 repoModel->setMenu();
timeout=0
cid=18104

- 正常设置版本库id @2
- 正常设置版本库id @3
- 正常设置版本库id @4
- 设置不存在版本库id @1
- 无权限用户设置版本库id @0
- 非镜像库不屏蔽 repoCodeScan/review 菜单 @repoCodeScan:1|review:1
- 镜像库屏蔽 repoCodeScan/review 两个一级菜单 @repoCodeScan:0|review:0

*/

zenData('user')->gen(20);
zenData('project')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(20);

global $dbh, $app;
$dataRoot = $app->getAppRoot() . 'test' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
foreach(array('ops_repo.sql', 'ops_repouser.sql') as $schema)
{
    $schemaFile = $dataRoot . $schema;
    if(file_exists($schemaFile)) $dbh->exec(file_get_contents($schemaFile));
}

zenData('ops_repo')->loadYaml('ops_repo')->gen(4);
zenData('ops_repouser')->loadYaml('ops_repouser')->gen(4);

/* 让 gitfoxModel::getServer() 返回非空，避免 getApiRoot() 返空字符串导致 processGitService 走到 sprintf(null, ...) fatal。 */
zenData('entry')->loadYaml('entry')->gen(1);

$repo = new repoModelTest();

r($repo->setMenuTest(2))             && p() && e('2');                       // 步骤1：正常设置版本库id=2
r($repo->setMenuTest(3))             && p() && e('3');                       // 步骤2：正常设置版本库id=3
r($repo->setMenuTest(4))             && p() && e('4');                       // 步骤3：正常设置版本库id=4
r($repo->setMenuTest(10001))         && p() && e('2');                       // 步骤4：不存在的 id 时 setMenu 内 key($repos) 回退到首条 id=2

su('user19');
r($repo->setMenuTest(3))             && p() && e('0');                       // 步骤5：无权限用户访问 → repoID 归 0

su('admin');
r($repo->setMenuMirrorCheckTest(2))  && p() && e('repoCodeScan:1|review:1'); // 步骤6：非镜像库 mirror=0，两菜单仍在
r($repo->setMenuMirrorCheckTest(5))  && p() && e('repoCodeScan:0|review:0'); // 步骤7：镜像库 mirror=1，两菜单被 unset

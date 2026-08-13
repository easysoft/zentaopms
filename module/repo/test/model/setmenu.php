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

- 步骤1：正常设置版本库id=2 @2
- 步骤2：再次设置版本库id=2 @2
- 步骤3：再次设置版本库id=2 @2
- 步骤4：不存在的 id 时 setMenu 内 key($repos) 回退到首条 id=2 @2
- 步骤5：无权限用户访问 → repoID 归 0 @0
- 步骤6：非镜像库 mirror=0，两菜单仍在 @repoCodeScan:1|review:1
- 步骤7：镜像库 id=2 mirror=1，两菜单被 unset @repoCodeScan:0|review:0

*/

zenData('user')->gen(20);
zenData('project')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(20);

global $tester, $app;
$dataRoot = $app->getAppRoot() . 'test' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
foreach(array('ops_repo.sql', 'ops_repouser.sql') as $schema)
{
    $schemaFile = $dataRoot . $schema;
    if(file_exists($schemaFile)) $tester->dao->exec(file_get_contents($schemaFile));
}

zenData('ops_repo')->loadYaml('ops_repo')->gen(4);

/* 让 gitfoxModel::getServer() 返回非空，避免 getApiRoot() 返空字符串导致 processGitService 走到 sprintf(null, ...) fatal。 */
zenData('entry')->loadYaml('entry')->gen(1);

$repo = new repoModelTest();

r($repo->setMenuTest(2))             && p() && e('2');                       // 步骤1：正常设置版本库id=2
r($repo->setMenuTest(2))             && p() && e('2');                       // 步骤2：再次设置版本库id=2
r($repo->setMenuTest(2))             && p() && e('2');                       // 步骤3：再次设置版本库id=2
r($repo->setMenuTest(10001))         && p() && e('2');                       // 步骤4：不存在的 id 时 setMenu 内 key($repos) 回退到首条 id=2

su('user19');
r($repo->setMenuTest(2))             && p() && e('0');                       // 步骤5：无权限用户访问 → repoID 归 0

su('admin');
r($repo->setMenuMirrorCheckTest(2))  && p() && e('repoCodeScan:1|review:1'); // 步骤6：非镜像库 mirror=0，两菜单仍在
$tester->dao->update(TABLE_REPO)->set('mirror')->eq(1)->where('id')->eq(2)->exec();
r($repo->setMenuMirrorCheckTest(2))  && p() && e('repoCodeScan:0|review:0'); // 步骤7：镜像库 id=2 mirror=1，两菜单被 unset
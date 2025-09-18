#!/usr/bin/env php
<?php

/**

title=测试 repoZen::prepareCreateRepo();
timeout=0
cid=0

- 步骤1：正常情况测试属性name @test-repo
- 步骤2：验证路径设置属性path @https://gitlabdev.qc.oop.cc/test-group/test-repo
- 步骤3：服务主机为空 @0
- 步骤4：命名空间为空 @0
- 步骤5：版本库名称为空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zendata('repo')->loadYaml('repo_preparecreaterepo', false, 2)->gen(5);
zendata('pipeline')->loadYaml('pipeline_preparecreaterepo', false, 2)->gen(3);

su('admin');

$repoTest = new repoTest();

$validRepo = new stdclass();
$validRepo->serviceHost = '1';
$validRepo->namespace = 'test-group';
$validRepo->name = 'test-repo';

$noServiceHostRepo = new stdclass();
$noServiceHostRepo->namespace = 'test-group';
$noServiceHostRepo->name = 'test-repo';

$noNamespaceRepo = new stdclass();
$noNamespaceRepo->serviceHost = '1';
$noNamespaceRepo->name = 'test-repo';

$noNameRepo = new stdclass();
$noNameRepo->serviceHost = '1';
$noNameRepo->namespace = 'test-group';

$invalidACLRepo = new stdclass();
$invalidACLRepo->serviceHost = '1';
$invalidACLRepo->namespace = 'test-group';
$invalidACLRepo->name = 'test-repo';

r($repoTest->prepareCreateRepoTest($validRepo)) && p('name') && e('test-repo'); // 步骤1：正常情况测试
r($repoTest->prepareCreateRepoTest($validRepo)) && p('path') && e('https://gitlabdev.qc.oop.cc/test-group/test-repo'); // 步骤2：验证路径设置
r($repoTest->prepareCreateRepoTest($noServiceHostRepo)) && p() && e('0'); // 步骤3：服务主机为空
r($repoTest->prepareCreateRepoTest($noNamespaceRepo)) && p() && e('0'); // 步骤4：命名空间为空
r($repoTest->prepareCreateRepoTest($noNameRepo)) && p() && e('0'); // 步骤5：版本库名称为空
#!/usr/bin/env php
<?php

/**

title=测试 gitModel::setRepo();
timeout=0
cid=16554

- 测试步骤1：正常有效的Git仓库对象属性result @1
- 测试步骤2：包含空client属性的仓库对象属性result @0
- 测试步骤3：验证client属性正确设置属性client @https://gitlabdev.qc.oop.cc
- 测试步骤4：验证repoRoot属性正确设置属性repoRoot @https://gitlabdev.qc.oop.cc/root/unittest1
- 测试步骤5：测试空client的处理属性result @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(1);
zenData('repo')->loadYaml('repo')->gen(1);
su('admin');

$gitTest = new gitModelTest();

// 准备测试数据
$validRepo = new stdclass();
$validRepo->client = 'https://gitlabdev.qc.oop.cc';
$validRepo->path = 'https://gitlabdev.qc.oop.cc/root/unittest1';

$invalidRepo = new stdclass();
$invalidRepo->client = '';
$invalidRepo->path = '/invalid/path';

$nullClientRepo = new stdclass();
$nullClientRepo->client = null;
$nullClientRepo->path = '/some/path';

r($gitTest->setRepoTest($validRepo)) && p('result') && e('1'); // 测试步骤1：正常有效的Git仓库对象
r($gitTest->setRepoTest($invalidRepo)) && p('result') && e('0'); // 测试步骤2：包含空client属性的仓库对象
r($gitTest->setRepoTest($validRepo)) && p('client') && e('https://gitlabdev.qc.oop.cc'); // 测试步骤3：验证client属性正确设置
r($gitTest->setRepoTest($validRepo)) && p('repoRoot') && e('https://gitlabdev.qc.oop.cc/root/unittest1'); // 测试步骤4：验证repoRoot属性正确设置
r($gitTest->setRepoTest($nullClientRepo)) && p('result') && e('0'); // 测试步骤5：测试空client的处理
#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiMirrorSync();
timeout=0
cid=0

- 步骤 1：查询不存在仓库的镜像同步不产生 dao 错误 @0
- 步骤 2：查询不存在仓库的镜像同步返回 failure @failure
- 步骤 3：查询不存在仓库的镜像同步返回仓库不存在 @仓库不存在。
- 步骤 4：查询不存在仓库的镜像同步返回值类型为 object @object
- 步骤 5：查询不存在仓库的镜像同步返回对象字段数为 4 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$missingRepoID = 999999;

r($gitfoxTest->apiMirrorSyncErrorTest($missingRepoID)) && p() && e('0');
r($gitfoxTest->apiMirrorSyncTest($missingRepoID)) && p('code') && e('failure');
r($gitfoxTest->apiMirrorSyncTest($missingRepoID)) && p('message') && e('仓库不存在。');
r($gitfoxTest->apiMirrorSyncTypeTest($missingRepoID)) && p() && e('object');
r($gitfoxTest->apiMirrorSyncCountTest($missingRepoID)) && p() && e('4');

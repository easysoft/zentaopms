#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSingleRepo();
timeout=0
cid=0

- 步骤 1：缓存命中时直接返回缓存对象 @cached-repo
- 步骤 2：HTTP 返回成功业务对象时正常解析仓库路径 @space/repo
- 步骤 3：HTTP 返回成功对象时设置 path_with_namespace 字段 @space/repo
- 步骤 4：HTTP 返回失败业务码时返回空数组 @0
- 步骤 5：HTTP 返回空字符串导致 json_decode 失败时返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
zenData('pipeline')->loadYaml('pipeline')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

$cached = new stdclass();
$cached->id   = 99;
$cached->name = 'cached-repo';
$gitfoxTest->setRepoCache(99, $cached);

$success = json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://10.0.4.2:3000/git/space/repo.git')));
$fail    = json_encode(array('code' => 'fail', 'message' => 'not found'));

r($gitfoxTest->apiGetSingleRepoTest(99)) && p('name') && e('cached-repo'); // 步骤 1
r($gitfoxTest->apiGetSingleRepoTest(1, $success)) && p('path') && e('space/repo'); // 步骤 2
r($gitfoxTest->apiGetSingleRepoTest(2, $success)) && p('path_with_namespace') && e('space/repo'); // 步骤 3
r(count((array)$gitfoxTest->apiGetSingleRepoTest(3, $fail))) && p() && e('0'); // 步骤 4
r(count((array)$gitfoxTest->apiGetSingleRepoTest(4, ''))) && p() && e('0'); // 步骤 5

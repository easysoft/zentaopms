#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetcommits();
timeout=0
cid=0

- 步骤 1：apiGetCommits 不产生 dao 错误 @0
- 步骤 2：apiGetCommits 返回 false @0
- 步骤 3：apiGetCommits 返回值类型为 bool @bool
- 步骤 4：apiGetCommits 的结果条数为 0 @0
- 步骤 5：带分页参数时仍返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiGetCommitsErrorTest(1, array())) && p() && e('0');
r($gitfoxTest->apiGetCommitsTest(1, array())) && p() && e('0');
r($gitfoxTest->apiGetCommitsTypeTest(1, array())) && p() && e('bool');
r($gitfoxTest->apiGetCommitsCountTest(1, array())) && p() && e('0');
r($gitfoxTest->apiGetCommitsTest(1, array('page' => 1))) && p() && e('0');

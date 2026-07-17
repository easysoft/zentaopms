#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::importFromProvider();
timeout=0
cid=0

- 测试从provider导入(空formData) @0
- 测试从provider导入(空repo) @0
- 测试从provider导入(正常数据无API) @0
- 测试从provider导入(无效repoID) @0
- 测试从provider导入(空引擎) @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$repo     = (object)array('id' => 1, 'name' => 'test-repo', 'providerID' => 0);
$formData = (object)array('engine' => 'gitlab', 'name' => 'imported-pipeline', 'providerID' => 0);
$emptyRepo = (object)array('providerID' => 0);
$invalidRepo = (object)array('id' => 9999, 'providerID' => 0);

$v1 = $tester->importFromProviderTest($repo, new stdclass());
$v2 = $tester->importFromProviderTest($emptyRepo, $formData);
$v3 = $tester->importFromProviderTest($repo, $formData);
$v4 = $tester->importFromProviderTest($invalidRepo, $formData);
$v5 = $tester->importFromProviderTest($repo, (object)array('engine' => '', 'providerID' => 0));

r($v1) && p() && e('0');
r($v2) && p() && e('0');
r($v3) && p() && e('0');
r($v4) && p() && e('0');
r($v5) && p() && e('0');

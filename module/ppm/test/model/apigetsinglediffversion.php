#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetSingleDiffVersion();
timeout=0
cid=17231

- 测试步骤1：无效主机ID @0
- 测试步骤2：有效参数正常情况
 - 属性id @43
 - 属性head_commit_sha @cedfe9a54614e71085e93a5a2e819617b48d43c5
- 测试步骤3：零值主机ID @0
- 测试步骤4：负数主机ID @0
- 测试步骤5：不存在的版本ID属性id @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(1);

su('admin');

$mrTest = new mrModelTest();

$hostID = array(
    'valid'   => 1,
    'invalid' => 999,
    'zero'    => 0,
    'negative' => -1
);

$projectID = '3';
$mrID      = 36;
$versionID = array(
    'valid'      => 43,
    'invalid'    => 999,
    'zero'       => 0,
    'negative'   => -1
);

r($mrTest->apiGetSingleDiffVersionTest($hostID['invalid'], $projectID, $mrID, $versionID['valid'])) && p() && e('0'); // 测试步骤1：无效主机ID
r($mrTest->apiGetSingleDiffVersionTest($hostID['valid'], $projectID, $mrID, $versionID['valid'])) && p('id,head_commit_sha') && e('43,cedfe9a54614e71085e93a5a2e819617b48d43c5'); // 测试步骤2：有效参数正常情况
r($mrTest->apiGetSingleDiffVersionTest($hostID['zero'], $projectID, $mrID, $versionID['valid'])) && p() && e('0'); // 测试步骤3：零值主机ID
r($mrTest->apiGetSingleDiffVersionTest($hostID['negative'], $projectID, $mrID, $versionID['valid'])) && p() && e('0'); // 测试步骤4：负数主机ID
r($mrTest->apiGetSingleDiffVersionTest($hostID['valid'], $projectID, $mrID, $versionID['invalid'])) && p('id') && e('~~'); // 测试步骤5：不存在的版本ID
#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiGetSingleMR();
timeout=0
cid=17232

- 测试步骤1：查询有效的仓库和合并请求ID
 - 属性title @test-merge（不要关闭或删除）
 - 属性state @opened
- 测试步骤2：查询不存在的仓库ID @0
- 测试步骤3：查询不存在的合并请求ID @0
- 测试步骤4：传入无效的负数仓库ID @0
- 测试步骤5：传入无效的负数合并请求ID @0
- 测试步骤6：测试返回对象的GitService属性设置属性gitService @gitlab
- 测试步骤7：测试flow属性默认值设置属性flow @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(1);

su('admin');

global $tester;
$mrTest = new mrTest();

r($mrTest->apiGetSingleMRTest(1, 36)) && p('title,state') && e('test-merge（不要关闭或删除）,opened'); // 测试步骤1：查询有效的仓库和合并请求ID
r($mrTest->apiGetSingleMRTest(999, 36)) && p() && e('0'); // 测试步骤2：查询不存在的仓库ID
r($mrTest->apiGetSingleMRTest(1, 999)) && p() && e('0'); // 测试步骤3：查询不存在的合并请求ID
r($mrTest->apiGetSingleMRTest(-1, 36)) && p() && e('0'); // 测试步骤4：传入无效的负数仓库ID
r($mrTest->apiGetSingleMRTest(1, -1)) && p() && e('0'); // 测试步骤5：传入无效的负数合并请求ID
r($mrTest->apiGetSingleMRTest(1, 36)) && p('gitService') && e('gitlab'); // 测试步骤6：测试返回对象的GitService属性设置
r($mrTest->apiGetSingleMRTest(1, 36)) && p('flow') && e('0'); // 测试步骤7：测试flow属性默认值设置
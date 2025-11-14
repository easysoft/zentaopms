#!/usr/bin/env php
<?php

/**

title=测试 buildModel::getRelatedReleases();
timeout=0
cid=15498

- 步骤1：基本产品ID数组查询 @0
- 步骤2：基本产品ID单个查询 @0
- 步骤3：空产品ID列表查询 @0
- 步骤4：不存在的产品ID查询 @0
- 步骤5：project对象过滤查询 @0
- 步骤6：execution对象过滤查询 @0
- 步骤7：带参数nowaitrelease过滤 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// su('admin'); // 跳过用户登录避免数据库错误

$buildTest = new buildTest();

r($buildTest->getRelatedReleasesTest(array(1))) && p() && e('0'); // 步骤1：基本产品ID数组查询
r($buildTest->getRelatedReleasesTest(1)) && p() && e('0'); // 步骤2：基本产品ID单个查询
r($buildTest->getRelatedReleasesTest(array())) && p() && e('0'); // 步骤3：空产品ID列表查询
r($buildTest->getRelatedReleasesTest(array(999))) && p() && e('0'); // 步骤4：不存在的产品ID查询
r($buildTest->getRelatedReleasesTest(array(1), '', false, 'project', 11)) && p() && e('0'); // 步骤5：project对象过滤查询
r($buildTest->getRelatedReleasesTest(array(1), '', false, 'execution', 101)) && p() && e('0'); // 步骤6：execution对象过滤查询
r($buildTest->getRelatedReleasesTest(array(1), '', false, '', 0, 'nowaitrelease')) && p() && e('0'); // 步骤7：带参数nowaitrelease过滤
#!/usr/bin/env php
<?php

/**

title=测试 buildModel::getRelatedReleases();
timeout=0
cid=0

- 步骤1：基本产品ID数组查询 @1
- 步骤2：基本产品ID单个查询 @1  
- 步骤3：空产品ID列表查询 @0
- 步骤4：project对象类型过滤查询 @1
- 步骤5：execution对象类型过滤查询 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('build')->loadYaml('build')->gen(20);
zenData('release')->loadYaml('release')->gen(10);

su('admin');

$buildTest = new buildTest();

r($buildTest->getRelatedReleasesTest(array(1))) && p() && e('1'); // 步骤1：基本产品ID数组查询
r($buildTest->getRelatedReleasesTest(1)) && p() && e('1'); // 步骤2：基本产品ID单个查询
r($buildTest->getRelatedReleasesTest(array())) && p() && e('0'); // 步骤3：空产品ID列表查询
r($buildTest->getRelatedReleasesTest(array(1), '', false, 'project', 11)) && p() && e('1'); // 步骤4：project对象过滤
r($buildTest->getRelatedReleasesTest(array(1), '', false, 'execution', 101)) && p() && e('1'); // 步骤5：execution对象过滤
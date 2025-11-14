#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::prepareCasesForBatchRun();
timeout=0
cid=19236

- 步骤1：正常情况获取测试单用例（用例3因版本过期被过滤） @2
- 步骤2：从测试用例模块获取用例 @2
- 步骤3：确认过滤自动化用例（过滤掉自动化用例1） @1
- 步骤4：空用例ID列表 @0
- 步骤5：无效产品ID @invalid_product_id

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

zenData('case')->loadYaml('case_preparecasesforbatchrun')->gen(10);
zenData('testrun')->loadYaml('testrun_preparecasesforbatchrun')->gen(5);
zenData('testtask')->loadYaml('testtask_preparecasesforbatchrun')->gen(3);

su('admin');

$testtaskTest = new testtaskZenTest();

r($testtaskTest->prepareCasesForBatchRunTest(1, 'id_asc', 'testtask', 1, '', array(1, 2, 3))) && p() && e('2'); // 步骤1：正常情况获取测试单用例（用例3因版本过期被过滤）
r($testtaskTest->prepareCasesForBatchRunTest(1, 'id_desc', 'testcase', 0, '', array(1, 2))) && p() && e('2'); // 步骤2：从测试用例模块获取用例
r($testtaskTest->prepareCasesForBatchRunTest(1, 'id_asc', 'testtask', 1, 'yes', array(1, 2, 3))) && p() && e('1'); // 步骤3：确认过滤自动化用例（过滤掉自动化用例1）
r($testtaskTest->prepareCasesForBatchRunTest(1, 'id_asc', 'testtask', 1, '', array())) && p() && e('0'); // 步骤4：空用例ID列表
r($testtaskTest->prepareCasesForBatchRunTest(0, 'id_asc', 'testtask', 1, '', array(1, 2))) && p() && e('invalid_product_id'); // 步骤5：无效产品ID
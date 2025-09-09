#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::getByList();
timeout=0
cid=0

- 步骤1：传入有效ID数组 @3
- 步骤2：传入空数组 @0
- 步骤3：传入不存在的ID数组 @0
- 步骤4：传入单个ID数组 @1
- 步骤5：传入混合ID数组 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zendata('project')->loadYaml('getbylist', false, 2)->gen(10);
zendata('projectproduct')->loadYaml('getbylist', false, 2)->gen(10);

global $tester;
$tester->loadModel('programplan');

r(count($tester->programplan->getByList(array(1, 2, 3)))) && p() && e(3); // 步骤1：传入有效ID数组
r(count($tester->programplan->getByList(array()))) && p() && e(0); // 步骤2：传入空数组
r(count($tester->programplan->getByList(array(999, 1000)))) && p() && e(0); // 步骤3：传入不存在的ID数组
r(count($tester->programplan->getByList(array(1)))) && p() && e(1); // 步骤4：传入单个ID数组
r(count($tester->programplan->getByList(array(1, 999, 2)))) && p() && e(2); // 步骤5：传入混合ID数组
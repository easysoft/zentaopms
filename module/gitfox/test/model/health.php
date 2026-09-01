#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getHealth();
timeout=0
cid=0

- 步骤 1：getHealth 返回对象 @1
- 步骤 2：getHealth status 为 healthy @healthy
- 步骤 3：getHealth version 非空 @1
- 步骤 4：checkHealth 返回合法的健康状态 @1
- 步骤 5：当前版本低于要求时 checkHealth 返回 upgrade @upgrade
- 步骤 6：当前版本高于要求时 checkHealth 仍返回 healthy @healthy

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();

r(is_object($gitfoxTest->getHealthTest())) && p() && e('1');                     // 步骤 1
r($gitfoxTest->getHealthStatusTest()) && p() && e('healthy');                    // 步骤 2
r(!empty($gitfoxTest->getHealthVersionTest())) && p() && e('1');                 // 步骤 3
r(in_array($gitfoxTest->checkHealthTest(), array('healthy', 'upgrade', 'beta'))) && p() && e('1'); // 步骤 4
r($gitfoxTest->checkHealthUpgradeTest()) && p() && e('upgrade');                 // 步骤 5
r($gitfoxTest->checkHealthNewerVersionTest()) && p() && e('healthy');            // 步骤 6

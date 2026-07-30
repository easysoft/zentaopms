#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::checkHealth();
timeout=0
cid=0

- 步骤 1：checkHealth 返回 healthy @healthy
- 步骤 2：连续两次 checkHealth 返回结果一致 @1
- 步骤 3：checkHealth 返回值类型为 string @string
- 步骤 4：重复调用 checkHealth 仍返回 healthy @healthy
- 步骤 5：再次调用 checkHealth 仍返回 healthy @healthy

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->checkHealthTest()) && p() && e('healthy');
r($gitfoxTest->checkHealthSameResultTest()) && p() && e('1');
r($gitfoxTest->checkHealthTypeTest()) && p() && e('string');
r($gitfoxTest->checkHealthTest()) && p() && e('healthy');
r($gitfoxTest->checkHealthTest()) && p() && e('healthy');

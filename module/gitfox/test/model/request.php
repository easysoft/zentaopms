#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::request();
timeout=0
cid=0

- 步骤 1：存在 GitFox 入口时 request 不产生 dao 错误 @0
- 步骤 2：GET /test 返回 false @0
- 步骤 3：GET /test 返回值类型为 bool @bool
- 步骤 4：移除 GitFox 入口后 request 仍返回 false @0
- 步骤 5：恢复 GitFox 入口后默认 GET 仍返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();
$gitfoxTest->seedGitFoxEntry();

r($gitfoxTest->requestErrorTest('/test', 'GET')) && p() && e('0');
r($gitfoxTest->requestTest('/test', 'GET')) && p() && e('0');
r($gitfoxTest->requestTypeTest('/test', 'GET')) && p() && e('bool');
$gitfoxTest->clearGitFoxEntry();
r($gitfoxTest->requestTest('/test')) && p() && e('0');
$gitfoxTest->seedGitFoxEntry();
r($gitfoxTest->requestTest('/test')) && p() && e('0');

#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getServer();
timeout=0
cid=0

- 步骤 1：存在 GitFox 入口时 url 正确 @http://localhost:3001
- 步骤 2：存在 GitFox 入口时 token 匹配预期 @1
- 步骤 3：移除 GitFox 入口后 token 为空 @1
- 步骤 4：getServer 返回对象属性数为 2 @2
- 步骤 5：getServer 返回值类型为 object @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();
$gitfoxTest->seedGitFoxEntry();

r($gitfoxTest->getServerTest()) && p('url') && e('http://localhost:3001');
r($gitfoxTest->getServerTokenMatchesTest('252f92b992f64597e84f910fd9135230')) && p() && e('1');
$gitfoxTest->clearGitFoxEntry();
r($gitfoxTest->getServerTokenEmptyTest()) && p() && e('1');
$gitfoxTest->seedGitFoxEntry();
r($gitfoxTest->getServerCountTest()) && p() && e('2');
r($gitfoxTest->getServerTypeTest()) && p() && e('object');

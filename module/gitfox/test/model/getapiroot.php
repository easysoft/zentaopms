#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getApiRoot();
timeout=0
cid=0

- 步骤 1：存在 GitFox 入口时 url 正确 @http://localhost:3001/api/v2%s
- 步骤 2：存在 GitFox 入口时 Authorization 头正确 @Authorization: 252f92b992f64597e84f910fd9135230
- 步骤 3：移除 GitFox 入口后 Authorization 头为空 @Authorization:
- 步骤 4：getApiRoot 返回对象属性数为 2 @2
- 步骤 5：getApiRoot 返回值类型为 object @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();
$gitfoxTest->seedGitFoxEntry();

r($gitfoxTest->getApiRootTest()) && p('url') && e('http://localhost:3001/api/v2%s');
r($gitfoxTest->getApiRootTest()) && p('header:0') && e('Authorization: 252f92b992f64597e84f910fd9135230');
$gitfoxTest->clearGitFoxEntry();
r($gitfoxTest->getApiRootTest()) && p('header:0') && e('Authorization: ');
$gitfoxTest->seedGitFoxEntry();
r($gitfoxTest->getApiRootCountTest()) && p() && e('2');
r($gitfoxTest->getApiRootTypeTest()) && p() && e('object');

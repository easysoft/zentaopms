#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getCommits();
timeout=0
cid=0

- 执行model模块的getCommits方法，参数是$repo, ''  @1
- 执行model模块的getCommits方法，参数是$repo, ''  @0
- 执行 @0
- 执行model模块的getCommits方法，参数是$repo, '', null, '', '', null  @1
- 执行model模块的getCommits方法，参数是$repo, ''  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$repo = new stdclass();
$repo->id       = 1;
$repo->scmType  = 'git';
$repo->client   = 'http://gitfox.test';
$repo->apiPath  = 'http://gitfox.test/api/v1/';
$repo->password = 'token';

r(is_array($model->getCommits($repo, ''))) && p() && e('1');
r(count($model->getCommits($repo, ''))) && p() && e('0');
r((int)dao::isError()) && p() && e('0');
r(is_array($model->getCommits($repo, '', null, '', '', null))) && p() && e('1');
r(is_array($model->getCommits($repo, ''))) && p() && e('1');
#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->buildSystemSearchForm();
timeout=0
cid=0

- 正常构建搜索表单 @1
- 带queryID @1
- 不同actionURL @1
- cacheSearchFunc=true @1
- cacheSearchFunc=false @1

*/

su('admin');

$repoTest = new repoModelTest();

r($repoTest->buildSystemSearchFormTest()) && p() && e('1');             // 正常构建搜索表单
r($repoTest->buildSystemSearchFormTest(1, '/repo-system')) && p() && e('1');  // 带queryID
r($repoTest->buildSystemSearchFormTest(0, '/repo-browse')) && p() && e('1');  // 不同actionURL
r($repoTest->buildSystemSearchFormTest(0, '/repo-system', true)) && p() && e('1');  // cacheSearchFunc=true
r($repoTest->buildSystemSearchFormTest(0, '/repo-system', false)) && p() && e('1'); // cacheSearchFunc=false
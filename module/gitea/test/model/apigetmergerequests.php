#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetMergeRequests();
timeout=0
cid=16561

- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是1, 'test/project'  @0
- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是999, 'test/project'  @0
- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是0, 'test/project'  @0
- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是1, ''  @0
- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是1, 'test/special-project'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitea.unittest.class.php';

$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('gitea{10}');
$table->name->range('gitea-server{1-5}, test-server{1-5}');
$table->url->range('http://gitea.test.com{10}');
$table->account->range('admin{10}');
$table->token->range('token123{10}');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

$giteaTest = new giteaTest();

r($giteaTest->apiGetMergeRequestsTest(1, 'test/project')) && p() && e('0');
r($giteaTest->apiGetMergeRequestsTest(999, 'test/project')) && p() && e('0');
r($giteaTest->apiGetMergeRequestsTest(0, 'test/project')) && p() && e('0');
r($giteaTest->apiGetMergeRequestsTest(1, '')) && p() && e('0');
r($giteaTest->apiGetMergeRequestsTest(1, 'test/special-project')) && p() && e('0');
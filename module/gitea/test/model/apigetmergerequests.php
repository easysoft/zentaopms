#!/usr/bin/env php
<?php

/**

title=测试 giteaModel::apiGetMergeRequests();
timeout=0
cid=0

- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是1, 'test/project'  @*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*

- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是999, 'test/project'  @*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*

- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是0, 'test/project'  @*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*

- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是1, ''  @*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*

- 执行giteaTest模块的apiGetMergeRequestsTest方法，参数是1, 'test/project@special'  @*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitea.unittest.class.php';

$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('gitea-repo{1-5}, test-repo{1-5}');
$table->SCM->range('Gitea{10}');
$table->serviceHost->range('http://gitea.test.com{10}');
$table->account->range('admin{10}');
$table->password->range('token123{10}');
$table->gen(10);

su('admin');

$giteaTest = new giteaTest();

r($giteaTest->apiGetMergeRequestsTest(1, 'test/project')) && p() && e("*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*");
r($giteaTest->apiGetMergeRequestsTest(999, 'test/project')) && p() && e("*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*");
r($giteaTest->apiGetMergeRequestsTest(0, 'test/project')) && p() && e("*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*");
r($giteaTest->apiGetMergeRequestsTest(1, '')) && p() && e("*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*");
r($giteaTest->apiGetMergeRequestsTest(1, 'test/project@special')) && p() && e("*foreach() argument must be of type array|object, null given*TypeError: giteaModel::apiGetMergeRequests(): Return value must be of type array, null returned*");
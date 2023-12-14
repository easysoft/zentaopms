#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->manageTagPrivs();
timeout=0
cid=1

- 维护正常的保护标签 @fail
- 使用错误的gitlabID维护保护标签 @success
- 使用错误的项目ID维护保护标签 @success

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$_POST['name'][0]         = 'keyword_tag';
$_POST['createAccess'][0] = 40;

$result1 = $gitlab->manageTagPrivsTest(1, 2);
$result2 = $gitlab->manageTagPrivsTest(0, 2);
$result3 = $gitlab->manageTagPrivsTest(1, 0);

r($result1) && p('') && e('fail');    // 维护正常的保护标签
r($result2) && p('') && e('success'); // 使用错误的gitlabID维护保护标签
r($result3) && p('') && e('success'); // 使用错误的项目ID维护保护标签
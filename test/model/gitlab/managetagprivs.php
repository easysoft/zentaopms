#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->manageTagPrivs();
cid=1
pid=1

维护已存在的保护标签            >> fail
使用错误的gitlabID维护保护标签  >> success
使用错误的项目ID维护保护标签    >> success

*/

$gitlab = new gitlabTest();

$_POST['tags'][0]         = '1230';
$_POST['createLevels'][0] = 40;

$result1 = $gitlab->manageTagPrivsTest(1, 1569);
$result2 = $gitlab->manageTagPrivsTest(0, 1569);
$result3 = $gitlab->manageTagPrivsTest(1, 0);

r($result1) && p('') && e('fail'); // 维护正常的保护标签
r($result2) && p('') && e('success'); // 使用错误的gitlabID维护保护标签
r($result3) && p('') && e('success'); // 使用错误的项目ID维护保护标签

#!/usr/bin/env php
<?php
/**

title=测试 fileModel->printFile();
timeout=0
cid=16493

- 测试非详情/编辑页面的情况 @1
- 测试编辑页面不展示删除按钮的情况 @0
- 测试编辑页面不展示编辑按钮的情况 @1
- 测试详情页面不展示删除按钮的情况 @0
- 测试详情页面不展示编辑按钮的情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('file')->gen(5);

$fileIdList     = range(1, 5);
$showDeleteList = array(true, false);
$showEdit       = array(true, false);

$fileTester = new fileModelTest();

$result1 = $fileTester->buildFileActionsTest($fileIdList[0], $showDeleteList[0], $showEdit[0]);
$result2 = $fileTester->buildFileActionsTest($fileIdList[1], $showDeleteList[1], $showEdit[0]);
$result3 = $fileTester->buildFileActionsTest($fileIdList[2], $showDeleteList[0], $showEdit[1]);
$result4 = $fileTester->buildFileActionsTest($fileIdList[3], $showDeleteList[1], $showEdit[0]);
$result5 = $fileTester->buildFileActionsTest($fileIdList[4], $showDeleteList[0], $showEdit[1]);

r(strpos($result1, '文件标题1') !== false) && p() && e('1'); // 测试非详情/编辑页面的情况
r(strpos($result2, '删除') === false)      && p() && e('0'); // 测试编辑页面不展示删除按钮的情况
r(strpos($result3, '编辑') === false)      && p() && e('1'); // 测试编辑页面不展示编辑按钮的情况
r(strpos($result4, '删除') === false)      && p() && e('0'); // 测试详情页面不展示删除按钮的情况
r(strpos($result5, '编辑') === false)      && p() && e('1'); // 测试详情页面不展示编辑按钮的情况

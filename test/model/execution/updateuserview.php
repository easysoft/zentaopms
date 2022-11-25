#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->updateUserView();
cid=1
pid=1

默认情况下的用户是否有201权限 >> 400
删除执行201后dev1用户是否有201权限 >> 0

*/

$executionID = 201;

$execution = new executionTest();
r(strpos($execution->updateUserViewTest($executionID), '201,'))                          && p() && e('400'); // 默认情况下的用户是否有201权限
$tester->dao->update(TABLE_EXECUTION)->set('deleted')->eq(1)->where('id')->eq($executionID)->exec();
r(strpos($execution->updateUserViewTest($executionID, 'sprint', array('dev1')), '201,')) && p() && e('0');   // 删除执行201后dev1用户是否有201权限
$db->restoreDB();

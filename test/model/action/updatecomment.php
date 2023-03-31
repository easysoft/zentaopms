#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->updateComment();
cid=1
pid=1

测试更新action 1的备注 >> 备注1
测试更新action 2的备注 >> 备注2
测试更新action 3的备注 >> 备注3
测试更新action 4的备注 >> 备注4
测试更新action 5的备注 >> 备注5

*/
$actionIDList = array('1', '2', '3', '4', '5');
$commentList  = array('备注1', '备注2', '备注3', '备注4', '备注5');

$action = new actionTest();

r($action->updateCommentTest($actionIDList[0], $commentList[0])) && p('comment') && e('备注1'); // 测试更新action 1的备注
r($action->updateCommentTest($actionIDList[1], $commentList[1])) && p('comment') && e('备注2'); // 测试更新action 2的备注
r($action->updateCommentTest($actionIDList[2], $commentList[2])) && p('comment') && e('备注3'); // 测试更新action 3的备注
r($action->updateCommentTest($actionIDList[3], $commentList[3])) && p('comment') && e('备注4'); // 测试更新action 4的备注
r($action->updateCommentTest($actionIDList[4], $commentList[4])) && p('comment') && e('备注5'); // 测试更新action 5的备注

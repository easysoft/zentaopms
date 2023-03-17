#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->create();
cid=1
pid=1

测试创建task 1 edited动态 >> task,,edited,
测试创建project 11 editestimate动态 >> project,,editestimate,
测试创建user 1 login动态 >> user,,login,
测试创建bug 1 closed动态 >> bug,,closed,
测试创建story 1 comment动态 >> story,,comments,测试备注
测试创建游客 logout动态 >> 0

*/

$objectTypeList = array('task', 'project', 'user', 'bug', 'story');
$objectIDList   = array('1', '11');
$actionTypeList = array('edited', 'editestimate', 'login', 'closed', 'comments', 'logout');
$actor          = 'guest';
$comment        = '测试备注';

$action = new actionTest();

r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[0]))                 && p('objectType,objectId,action,comment') && e('task,,edited,');            // 测试创建task 1 edited动态
r($action->createTest($objectTypeList[1], $objectIDList[1], $actionTypeList[1]))                 && p('objectType,objectId,action,comment') && e('project,,editestimate,');   // 测试创建project 11 editestimate动态
r($action->createTest($objectTypeList[2], $objectIDList[0], $actionTypeList[2]))                 && p('objectType,objectId,action,comment') && e('user,,login,');             // 测试创建user 1 login动态
r($action->createTest($objectTypeList[3], $objectIDList[0], $actionTypeList[3]))                 && p('objectType,objectId,action,comment') && e('bug,,closed,');             // 测试创建bug 1 closed动态
r($action->createTest($objectTypeList[4], $objectIDList[0], $actionTypeList[4], $comment))       && p('objectType,objectId,action,comment') && e('story,,comments,测试备注'); // 测试创建story 1 comment动态
r($action->createTest($objectTypeList[2], $objectIDList[0], $actionTypeList[5], '', '', $actor)) && p()                                     && e('0');                        // 测试创建游客 logout动态

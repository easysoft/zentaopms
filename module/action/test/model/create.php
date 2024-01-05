#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('actionrecent')->gen(0);
zdTable('action')->gen(0);
zdTable('file')->gen(0);
zdTable('task')->gen(1);
zdTable('story')->gen(1);
zdTable('project')->config('execution')->gen(1);

/**

title=测试 actionModel->create();
timeout=0
cid=1

- 测试创建task，1，edited
 - 属性objectType @task
 - 属性objectID @1
 - 属性actor @guest
- 测试创建user，1，logout @0
- 测试创建task，1，commented, @0
- 测试创建task，1，commented, '测试备注'
 - 属性objectType @task
 - 属性objectID @1
 - 属性comment @测试备注
 - 属性product @,1,
 - 属性project @11
 - 属性execution @101
- 测试创建story,1,reviewpassed
 - 属性objectType @story
 - 属性objectID @1
 - 属性actor @系统
 - 属性action @reviewpassed
- 测试创建story,1,reviewrejected
 - 属性objectType @story
 - 属性objectID @1
 - 属性actor @系统
 - 属性action @reviewrejected
- 测试创建story,1,reviewclarified
 - 属性objectType @story
 - 属性objectID @1
 - 属性actor @系统
 - 属性action @reviewclarified
- 测试创建story,1,reviewreverted
 - 属性objectType @story
 - 属性objectID @1
 - 属性actor @系统
 - 属性action @reviewreverted
- 测试创建story,1,synctwins
 - 属性objectType @story
 - 属性objectID @1
 - 属性actor @系统
 - 属性action @synctwins
- 测试升级中的并且版本号小于18.7的情况，不创建actionrecent @8
- 测试升级中的并且版本号大于18.7的情况，创建actionrecent @9

*/
$objectTypeList      = array('task', 'project', 'user', 'bug', 'story');
$objectIDList        = array('1', '11');
$actionTypeList      = array('commented', 'edited', 'editestimate', 'login', 'closed', 'comments', 'logout');
$storyActionTypeList = array('reviewpassed', 'reviewrejected', 'reviewclarified', 'reviewreverted', 'synctwins');
$actor               = 'guest';
$comment             = array('', '测试备注', '<p><img src="ccreate?m=file&f=read&t=jpeg&fileID=1"></p>');
$uid                 = array('', uniqid());
$autoDelete          = array(true, false);
$versionList         = array('18.1', '18.7');

$action = new actionTest();

r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[1])) && p('objectType;objectID;actor') && e('task;1;guest'); //测试创建task，1，edited
r($action->createTest($objectTypeList[2], $objectIDList[0], $actionTypeList[6])) && p('') && e('0');                                     //测试创建user，1，logout

su('admin');

r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[0], ''))          && p('') && e(0);                                             //测试创建task，1，commented,
r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[0], $comment[1])) && p('objectType|objectID|comment|product|project|execution', '|') && e('task|1|测试备注|,1,|11|101');  //测试创建task，1，commented, '测试备注'

r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[0])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewpassed');    //测试创建story,1,reviewpassed
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[1])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewrejected');  //测试创建story,1,reviewrejected
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[2])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewclarified'); //测试创建story,1,reviewclarified
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[3])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewreverted');  //测试创建story,1,reviewreverted
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[4])) && p('objectType,objectID,actor,action') && e('story,1,系统,synctwins');       //测试创建story,1,synctwins

global $tester;
$tester->app->upgrading = true;
$version = $tester->dao->select('value')->from(TABLE_CONFIG)->where('`key`')->eq('version')->andWhere('owner')->eq('system')->andWhere('module')->eq('common')->fetch('value');

$action->createTest($objectTypeList[0], $objectIDList[0], $storyActionTypeList[0], $comment[0], '', '', '', $versionList[0]);
r($tester->dao->select('count(*) as count')->from('zt_actionrecent')->fetch('count')) && p() && e('8');  //测试升级中的并且版本号小于18.7的情况，不创建actionrecent

unset(dao::$cache['zt_actionrecent']);
$action->createTest($objectTypeList[0], $objectIDList[0], $storyActionTypeList[0], $comment[0], '', '', '', $versionList[1]);
r($tester->dao->select('count(*) as count')->from('zt_actionrecent')->fetch('count')) && p() && e('9'); //测试升级中的并且版本号大于18.7的情况，创建actionrecent

$tester->dao->update(TABLE_CONFIG)->set('value')->eq($version)->where('`key`')->eq('version')->andWhere('owner')->eq('system')->andWhere('module')->eq('common')->exec();
$tester->app->upgrading = false;
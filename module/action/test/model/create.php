#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
include dirname(__FILE__, 4) . '/file/test/file.class.php';

zdTable('file')->config('file')->gen(1);
zdTable('actionrecent')->gen(0);
zdTable('action')->gen(0);
$resouceImage = dirname(__FILE__) . '/yaml/create/1314382308742pf5';
$targetImage  = dirname(__FILE__, 5) . '/test/www/data/upload/1/202309/1314382308742pf5';
exec("cp {$resouceImage} {$targetImage}");

/**

title=测试 actionModel->create();
cid=1
pid=1

游客状态

测试创建task 1 edited >> task,1,guest
测试创建user,1 logout >> 0

切换admin账号

测试创建task 1 commented          >> 0
测试创建task 1 commented 测试备注 >> task,1,测试备注

测试创建story 1 reviewpassed    >> story,1,系统,reviewpassed
测试创建story 1 reviewrejected  >> story,1,系统,reviewrejected
测试创建story 1 reviewclarified >> story,1,系统,reviewclarified
测试创建story 1 reviewreverted  >> story,1,系统,reviewreverted
测试创建story 1 synctwins       >> story,1,系统,synctwins

测试创建task 1 edited uniqid()     >> task,1,admin,edited
测试创建task 11 commented uniqid() >> task,11,admin,commented

*/
$objectTypeList      = array('task', 'project', 'user', 'bug', 'story');
$objectIDList        = array('1', '11');
$actionTypeList      = array('commented', 'edited', 'editestimate', 'login', 'closed', 'comments', 'logout');
$storyActionTypeList = array('reviewpassed', 'reviewrejected', 'reviewclarified', 'reviewreverted', 'synctwins');
$actor               = 'guest';
$comment             = array('', '测试备注', '<p><img src="ccreate?m=file&f=read&t=jpeg&fileID=1"></p>');
$uid                 = array('', uniqid());
$autoDelete          = array(true, false);

$action = new actionTest();

$file = new fileTest();

r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[1])) && p('objectType;objectID;actor') && e('task;1;guest'); //测试创建task，1，edited
r($action->createTest($objectTypeList[2], $objectIDList[0], $actionTypeList[6])) && p('') && e('0');                                     //测试创建user，1，logout

su('admin');

r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[0], ''))          && p('') && e(0);                                             //测试创建task，1，commented,
r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[0], $comment[1])) && p('objectType,objectID,comment') && e('task,1,测试备注');  //测试创建task，1，commented, '测试备注'

r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[0])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewpassed');    //测试创建story,1,reviewpassed
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[1])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewrejected');  //测试创建story,1,reviewrejected
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[2])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewclarified'); //测试创建story,1,reviewclarified
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[3])) && p('objectType,objectID,actor,action') && e('story,1,系统,reviewreverted');  //测试创建story,1,reviewreverted
r($action->createTest($objectTypeList[4], $objectIDList[0], $storyActionTypeList[4])) && p('objectType,objectID,actor,action') && e('story,1,系统,synctwins');       //测试创建story,1,synctwins

r($action->createTest($objectTypeList[0], $objectIDList[0], $actionTypeList[1], '', '', '', $uid[0]))                          && p('objectType,objectID,actor,action') && e('task,1,admin,edited');     //测试创建task,1,edited,,,,uniqid()
r($action->createTest($objectTypeList[0], $objectIDList[1], $actionTypeList[0], $comment[2], '', '', $uid[1], $autoDelete[1])) && p('objectType,objectID,actor,action') && e('task,11,admin,commented'); //测试创建task,1,edited,,,,uniqid()
r($file->getByIdTest(1))                                                                                                       && p('objectType;objectID')              && e('task;11');                 //测试文件是否更新成功
r($action->createTest($objectTypeList[0], $objectIDList[1], $actionTypeList[0], $comment[2], '', '', $uid[1], $autoDelete[0])) && p('objectType,objectID,actor,action') && e('task,11,admin,commented'); //测试创建task,1,edited,,,,uniqid()
r($file->getByIdTest(1))                                                                                                       && p('')                                 && e('0');                       //测试文件是否更新成功

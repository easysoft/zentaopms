#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

$action = zdTable('action');
$action->id->range('101-105');
$action->objectType->range("product,story,productplan,release,project");
$action->action->range("common,extra,opened,created,changed");
$action->comment->range("1-5")->prefix("这是一个系统日志测试备注");
$action->gen(5);

//zdTable('action')->gen(5);

/**

title=测试 actionModel->getById();
cid=1
pid=1

测试获取动态1的信息 >> product,,common,这是一个系统日志测试备注1
测试获取动态2的信息 >> story,,extra,这是一个系统日志测试备注2
测试获取动态3的信息 >> productplan,,opened,这是一个系统日志测试备注3
测试获取动态4的信息 >> release,,created,这是一个系统日志测试备注4
测试获取动态5的信息 >> project,,changed,这是一个系统日志测试备注5

*/

$actionIDList = array('101', '102', '103', '104', '105');

$action = new actionTest();

r($action->getByIdTest($actionIDList[0])) && p('objectType,objectId,action,comment') && e('product,,common,这是一个系统日志测试备注1');     // 测试获取动态1的信息
r($action->getByIdTest($actionIDList[1])) && p('objectType,objectId,action,comment') && e('story,,extra,这是一个系统日志测试备注2');        // 测试获取动态2的信息
r($action->getByIdTest($actionIDList[2])) && p('objectType,objectId,action,comment') && e('productplan,,opened,这是一个系统日志测试备注3'); // 测试获取动态3的信息
r($action->getByIdTest($actionIDList[3])) && p('objectType,objectId,action,comment') && e('release,,created,这是一个系统日志测试备注4');    // 测试获取动态4的信息
r($action->getByIdTest($actionIDList[4])) && p('objectType,objectId,action,comment') && e('project,,changed,这是一个系统日志测试备注5');    // 测试获取动态5的信息

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(5);

/**

title=测试 actionModel->getById();
timeout=0
cid=1

- 测试获取动态1的信息
 - 属性objectType @product
 - 属性objectId @~~
 - 属性action @common
 - 属性comment @这是一个系统日志测试备注1
- 测试获取动态2的信息
 - 属性objectType @story
 - 属性objectId @~~
 - 属性action @extra
 - 属性comment @这是一个系统日志测试备注2
- 测试获取动态3的信息
 - 属性objectType @productplan
 - 属性objectId @~~
 - 属性action @opened
 - 属性comment @这是一个系统日志测试备注3
- 测试获取动态4的信息
 - 属性objectType @release
 - 属性objectId @~~
 - 属性action @created
 - 属性comment @这是一个系统日志测试备注4
- 测试获取动态5的信息
 - 属性objectType @project
 - 属性objectId @~~
 - 属性action @changed
 - 属性comment @这是一个系统日志测试备注5

*/

$actionIDList = array('101', '102', '103', '104', '105');

$action = new actionTest();

r($action->getByIdTest($actionIDList[0])) && p('objectType,objectId,action,comment') && e('product,~~,common,这是一个系统日志测试备注1');     // 测试获取动态1的信息
r($action->getByIdTest($actionIDList[1])) && p('objectType,objectId,action,comment') && e('story,~~,extra,这是一个系统日志测试备注2');        // 测试获取动态2的信息
r($action->getByIdTest($actionIDList[2])) && p('objectType,objectId,action,comment') && e('productplan,~~,opened,这是一个系统日志测试备注3'); // 测试获取动态3的信息
r($action->getByIdTest($actionIDList[3])) && p('objectType,objectId,action,comment') && e('release,~~,created,这是一个系统日志测试备注4');    // 测试获取动态4的信息
r($action->getByIdTest($actionIDList[4])) && p('objectType,objectId,action,comment') && e('project,~~,changed,这是一个系统日志测试备注5');    // 测试获取动态5的信息
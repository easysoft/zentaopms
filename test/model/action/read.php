#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->read();
cid=1
pid=1

测试对action 1 进行阅读操作 >> product,1,1
测试对action 2 进行阅读操作 >> story,2,1
测试对action 3 进行阅读操作 >> productplan,3,1
测试对action 4 进行阅读操作 >> release,4,1
测试对action 5 进行阅读操作 >> project,5,1

*/

$objectType = array('product', 'story', 'productplan', 'release', 'project');
$objectID   = array(1,2,3,4,5);

$action = new actionTest();

r($action->readTest($objectType[0], $objectID[0])) && p('0:objectType,objectID,read') && e('product,1,1');     // 测试对action 1 进行阅读操作
r($action->readTest($objectType[1], $objectID[1])) && p('0:objectType,objectID,read') && e('story,2,1');       // 测试对action 2 进行阅读操作
r($action->readTest($objectType[2], $objectID[2])) && p('0:objectType,objectID,read') && e('productplan,3,1'); // 测试对action 3 进行阅读操作
r($action->readTest($objectType[3], $objectID[3])) && p('0:objectType,objectID,read') && e('release,4,1');     // 测试对action 4 进行阅读操作
r($action->readTest($objectType[4], $objectID[4])) && p('0:objectType,objectID,read') && e('project,5,1');     // 测试对action 5 进行阅读操作

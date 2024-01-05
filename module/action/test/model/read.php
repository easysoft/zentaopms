#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(5);

/**

title=测试 actionModel->read();
timeout=0
cid=1

- 测试对action 1 进行阅读操作
 - 第0条的objectType属性 @product
 - 第0条的objectID属性 @1
 - 第0条的read属性 @1
- 测试对action 2 进行阅读操作
 - 第0条的objectType属性 @story
 - 第0条的objectID属性 @2
 - 第0条的read属性 @1
- 测试对action 3 进行阅读操作
 - 第0条的objectType属性 @productplan
 - 第0条的objectID属性 @3
 - 第0条的read属性 @1
- 测试对action 4 进行阅读操作
 - 第0条的objectType属性 @release
 - 第0条的objectID属性 @4
 - 第0条的read属性 @1
- 测试对action 5 进行阅读操作
 - 第0条的objectType属性 @project
 - 第0条的objectID属性 @5
 - 第0条的read属性 @1

*/

$objectType = array('product', 'story', 'productplan', 'release', 'project');
$objectID   = array(1,2,3,4,5);

$action = new actionTest();

r($action->readTest($objectType[0], $objectID[0])) && p('0:objectType,objectID,read') && e('product,1,1');     // 测试对action 1 进行阅读操作
r($action->readTest($objectType[1], $objectID[1])) && p('0:objectType,objectID,read') && e('story,2,1');       // 测试对action 2 进行阅读操作
r($action->readTest($objectType[2], $objectID[2])) && p('0:objectType,objectID,read') && e('productplan,3,1'); // 测试对action 3 进行阅读操作
r($action->readTest($objectType[3], $objectID[3])) && p('0:objectType,objectID,read') && e('release,4,1');     // 测试对action 4 进行阅读操作
r($action->readTest($objectType[4], $objectID[4])) && p('0:objectType,objectID,read') && e('project,5,1');     // 测试对action 5 进行阅读操作
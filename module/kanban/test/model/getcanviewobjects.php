#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';

zdTable('kanban')->gen(100);
zdTable('kanbanspace')->gen(100);

/**

title=测试 kanbanModel->getCanViewObjects();
timeout=0
cid=1

- 测试查询admin有权限查看的看板个数 @32
- 测试查询admin有权限查看的空间个数 @33
- 测试查询admin有权限查看的未关闭看板个数 @100
- 测试查询admin有权限查看的私人看板个数 @32
- 测试查询admin有权限查看的协同看板个数 @100
- 测试查询admin有权限查看的公共看板个数 @100
- 测试查询admin有权限查看的参与看板个数 @0
- 测试查询admin有权限查看的看板个数 @32
- 测试查询admin有权限查看的看板个数 @32
- 测试查询po1有权限查看的看板个数 @32
- 测试查询po2有权限查看的看板个数 @32
- 测试查询user1有权限查看的看板个数 @32
- 测试查询user2有权限查看的看板个数 @32
- 测试查询pm1有权限查看的看板个数 @32
- 测试查询pm2有权限查看的看板个数 @32

*/

$kanban = new kanbanTest();

$userList   = array('admin', 'po1', 'po2', 'user1', 'user2', 'pm1', 'pm2');
$objectType = array('kanban', 'kanbanspace');
$paramList  = array('noclosed', 'private', 'cooperation', 'public', 'involved');

r($kanban->getCanViewObjectsTest($userList[0]))                                && p() && e('32');  // 测试查询admin有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[0], $objectType[1]))                && p() && e('33');  // 测试查询admin有权限查看的空间个数
r($kanban->getCanViewObjectsTest($userList[0], $objectType[0], $paramList[0])) && p() && e('100'); // 测试查询admin有权限查看的未关闭看板个数
r($kanban->getCanViewObjectsTest($userList[0], $objectType[0], $paramList[1])) && p() && e('32');  // 测试查询admin有权限查看的私人看板个数
r($kanban->getCanViewObjectsTest($userList[0], $objectType[0], $paramList[2])) && p() && e('100'); // 测试查询admin有权限查看的协同看板个数
r($kanban->getCanViewObjectsTest($userList[0], $objectType[0], $paramList[3])) && p() && e('100'); // 测试查询admin有权限查看的公共看板个数
r($kanban->getCanViewObjectsTest($userList[0], $objectType[0], $paramList[4])) && p() && e('0');   // 测试查询admin有权限查看的参与看板个数
r($kanban->getCanViewObjectsTest($userList[0]))                                && p() && e('32');  // 测试查询admin有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[0]))                                && p() && e('32');  // 测试查询admin有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[1]))                                && p() && e('32');  // 测试查询po1有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[2]))                                && p() && e('32');  // 测试查询po2有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[3]))                                && p() && e('32');  // 测试查询user1有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[4]))                                && p() && e('32');  // 测试查询user2有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[5]))                                && p() && e('32');  // 测试查询pm1有权限查看的看板个数
r($kanban->getCanViewObjectsTest($userList[6]))                                && p() && e('32');  // 测试查询pm2有权限查看的看板个数
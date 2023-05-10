#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('user')->gen(5);
zdTable('task')->gen(5);

/**

title=taskModel->updateLastEdited();
timeout=0
cid=1

*/

$task = new taskTest();

$taskIDList = range(0, 5);
$userList   = array('admin', 'user1', 'user2', 'user3', 'user4');

su($userList[0]);
r($task->updateLastEditedTest($taskIDList[0])) && p('lastEditedBy') && e('0');     // 测试admin用户登录时更新任务1最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[1])) && p('lastEditedBy') && e('admin'); // 测试admin用户登录时更新任务2最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[2])) && p('lastEditedBy') && e('admin'); // 测试admin用户登录时更新任务3最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[3])) && p('lastEditedBy') && e('admin'); // 测试admin用户登录时更新任务4最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[4])) && p('lastEditedBy') && e('admin'); // 测试admin用户登录时更新任务5最后编辑信息是否成功
su($userList[1]);
r($task->updateLastEditedTest($taskIDList[0])) && p('lastEditedBy') && e('0');     // 测试user1用户登录时更新任务1最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[1])) && p('lastEditedBy') && e('user1'); // 测试user1用户登录时更新任务2最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[2])) && p('lastEditedBy') && e('user1'); // 测试user1用户登录时更新任务3最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[3])) && p('lastEditedBy') && e('user1'); // 测试user1用户登录时更新任务4最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[4])) && p('lastEditedBy') && e('user1'); // 测试user1用户登录时更新任务5最后编辑信息是否成功
su($userList[2]);
r($task->updateLastEditedTest($taskIDList[0])) && p('lastEditedBy') && e('0');     // 测试user2用户登录时更新任务1最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[1])) && p('lastEditedBy') && e('user2'); // 测试user2用户登录时更新任务2最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[2])) && p('lastEditedBy') && e('user2'); // 测试user2用户登录时更新任务3最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[3])) && p('lastEditedBy') && e('user2'); // 测试user2用户登录时更新任务4最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[4])) && p('lastEditedBy') && e('user2'); // 测试user2用户登录时更新任务5最后编辑信息是否成功
su($userList[3]);
r($task->updateLastEditedTest($taskIDList[0])) && p('lastEditedBy') && e('0');     // 测试user3用户登录时更新任务1最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[1])) && p('lastEditedBy') && e('user3'); // 测试user3用户登录时更新任务2最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[2])) && p('lastEditedBy') && e('user3'); // 测试user3用户登录时更新任务3最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[3])) && p('lastEditedBy') && e('user3'); // 测试user3用户登录时更新任务4最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[4])) && p('lastEditedBy') && e('user3'); // 测试user3用户登录时更新任务5最后编辑信息是否成功
su($userList[4]);
r($task->updateLastEditedTest($taskIDList[0])) && p('lastEditedBy') && e('0');     // 测试user4用户登录时更新任务1最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[1])) && p('lastEditedBy') && e('user4'); // 测试user4用户登录时更新任务2最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[2])) && p('lastEditedBy') && e('user4'); // 测试user4用户登录时更新任务3最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[3])) && p('lastEditedBy') && e('user4'); // 测试user4用户登录时更新任务4最后编辑信息是否成功
r($task->updateLastEditedTest($taskIDList[4])) && p('lastEditedBy') && e('user4'); // 测试user4用户登录时更新任务5最后编辑信息是否成功

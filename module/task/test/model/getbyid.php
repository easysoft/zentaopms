#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project', true)->gen(5);
zenData('task')->loadYaml('task', true)->gen(9);
zenData('taskteam')->loadYaml('taskteam', true)->gen(6);

/**

title=taskModel->getByID();
timeout=0
cid=18790

- 测试获取taskID=1的任务信息
 - 属性id @1
 - 属性name @开发任务11
 - 属性status @wait
 - 属性desc @这里是任务描述1
- 测试获取taskID=2的任务信息
 - 属性id @2
 - 属性name @开发任务12
 - 属性status @doing
 - 属性desc @这里是任务描述2
- 测试获取taskID=3的任务信息
 - 属性id @3
 - 属性name @开发任务13
 - 属性status @done
 - 属性desc @这里是任务描述3
- 测试获取taskID=4的任务信息
 - 属性id @4
 - 属性name @开发任务14
 - 属性status @cancel
 - 属性desc @这里是任务描述4
- 测试获取taskID=5的任务信息
 - 属性id @5
 - 属性name @开发任务15
 - 属性status @closed
 - 属性desc @这里是任务描述5
- 测试获取taskID=7的任务信息
 - 属性id @7
 - 属性parent @6
 - 属性parentName @开发任务16
- 测试获取taskID=8的任务信息
 - 第team[1]条的account属性 @admin
 - 第team[1]条的estimate属性 @1.00
 - 第team[1]条的status属性 @wait
- 测试获取taskID=9的任务信息
 - 第team[4]条的account属性 @admin
 - 第team[4]条的estimate属性 @4.00
 - 第team[4]条的status属性 @wait
- 测试获取taskID=1的任务信息
 - 属性id @1
 - 属性name @开发任务11
 - 属性status @wait
 - 属性desc @这里是任务描述1
- 测试获取taskID=2的任务信息
 - 属性id @2
 - 属性name @开发任务12
 - 属性status @doing
 - 属性desc @这里是任务描述2
- 测试获取taskID=3的任务信息
 - 属性id @3
 - 属性name @开发任务13
 - 属性status @done
 - 属性desc @这里是任务描述3
- 测试获取taskID=4的任务信息
 - 属性id @4
 - 属性name @开发任务14
 - 属性status @cancel
 - 属性desc @这里是任务描述4
- 测试获取taskID=5的任务信息
 - 属性id @5
 - 属性name @开发任务15
 - 属性status @closed
 - 属性desc @这里是任务描述5
- 测试获取taskID=6的任务信息
 - 属性id @6
 - 属性name @开发任务16
 - 属性status @wait
 - 属性desc @这里是任务描述6
- 测试获取taskID=7的任务信息
 - 属性id @7
 - 属性parent @6
 - 属性parentName @开发任务16
- 测试获取taskID=8的任务信息
 - 第team[1]条的account属性 @admin
 - 第team[1]条的estimate属性 @1.00
 - 第team[1]条的status属性 @wait
- 测试获取taskID=9的任务信息
 - 第team[4]条的account属性 @admin
 - 第team[4]条的estimate属性 @4.00
 - 第team[4]条的status属性 @wait

*/

$taskIdList = range(1, 9);

$taskModel = $tester->loadModel('task');
r($taskModel->getByID($taskIdList[0])) && p('id,name,status,desc')             && e('1,开发任务11,wait,这里是任务描述1');   // 测试获取taskID=1的任务信息
r($taskModel->getByID($taskIdList[1])) && p('id,name,status,desc')             && e('2,开发任务12,doing,这里是任务描述2');  // 测试获取taskID=2的任务信息
r($taskModel->getByID($taskIdList[2])) && p('id,name,status,desc')             && e('3,开发任务13,done,这里是任务描述3');   // 测试获取taskID=3的任务信息
r($taskModel->getByID($taskIdList[3])) && p('id,name,status,desc')             && e('4,开发任务14,cancel,这里是任务描述4'); // 测试获取taskID=4的任务信息
r($taskModel->getByID($taskIdList[4])) && p('id,name,status,desc')             && e('5,开发任务15,closed,这里是任务描述5'); // 测试获取taskID=5的任务信息
r($taskModel->getByID($taskIdList[6])) && p('id,parent,parentName')            && e('7,6,开发任务16');                      // 测试获取taskID=7的任务信息
r($taskModel->getByID($taskIdList[7])) && p('team[1]:account,estimate,status') && e('admin,1.00,wait');                     // 测试获取taskID=8的任务信息
r($taskModel->getByID($taskIdList[8])) && p('team[4]:account,estimate,status') && e('admin,4.00,wait');                     // 测试获取taskID=9的任务信息

r($taskModel->getByID($taskIdList[0], true)) && p('id,name,status,desc')             && e('1,开发任务11,wait,这里是任务描述1');   // 测试获取taskID=1的任务信息
r($taskModel->getByID($taskIdList[1], true)) && p('id,name,status,desc')             && e('2,开发任务12,doing,这里是任务描述2');  // 测试获取taskID=2的任务信息
r($taskModel->getByID($taskIdList[2], true)) && p('id,name,status,desc')             && e('3,开发任务13,done,这里是任务描述3');   // 测试获取taskID=3的任务信息
r($taskModel->getByID($taskIdList[3], true)) && p('id,name,status,desc')             && e('4,开发任务14,cancel,这里是任务描述4'); // 测试获取taskID=4的任务信息
r($taskModel->getByID($taskIdList[4], true)) && p('id,name,status,desc')             && e('5,开发任务15,closed,这里是任务描述5'); // 测试获取taskID=5的任务信息
r($taskModel->getByID($taskIdList[5], true)) && p('id,name,status,desc')             && e('6,开发任务16,wait,这里是任务描述6');   // 测试获取taskID=6的任务信息
r($taskModel->getByID($taskIdList[6], true)) && p('id,parent,parentName')            && e('7,6,开发任务16');                    // 测试获取taskID=7的任务信息
r($taskModel->getByID($taskIdList[7], true)) && p('team[1]:account,estimate,status') && e('admin,1.00,wait');                   // 测试获取taskID=8的任务信息
r($taskModel->getByID($taskIdList[8], true)) && p('team[4]:account,estimate,status') && e('admin,4.00,wait');                  // 测试获取taskID=9的任务信息
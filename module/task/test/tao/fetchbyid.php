#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('task')->gen(5);

/**

title=taskModel->fetchByID();
timeout=0
cid=1

*/

$task = $tester->loadModel('task');

r($task->fetchByID(-1)) && p('id,project,execution,name,type,pri,status') && e('0,0,0,0,0,0,0');                        // 测试获取id为-1的任务
r($task->fetchByID(0))  && p('id,project,execution,name,type,pri,status') && e('0,0,0,0,0,0,0');                        // 测试获取id为0的任务
r($task->fetchByID(1))  && p('id,project,execution,name,type,pri,status') && e('1,11,101,开发任务11,design,1,wait');    // 测试获取id为1的任务
r($task->fetchByID(2))  && p('id,project,execution,name,type,pri,status') && e('2,12,102,开发任务12,devel,2,doing');    // 测试获取id为2的任务
r($task->fetchByID(3))  && p('id,project,execution,name,type,pri,status') && e('3,13,103,开发任务13,test,3,done');      // 测试获取id为3的任务
r($task->fetchByID(4))  && p('id,project,execution,name,type,pri,status') && e('4,14,104,开发任务14,study,4,pause');    // 测试获取id为4的任务
r($task->fetchByID(5))  && p('id,project,execution,name,type,pri,status') && e('5,15,105,开发任务15,discuss,1,cancel'); // 测试获取id为5的任务

r($task->fetchByID(-1, 'id'))        && p() && e('0'); // 测试获取id为-1的任务的id字段
r($task->fetchByID(-1, 'project'))   && p() && e('0'); // 测试获取id为-1的任务的project字段
r($task->fetchByID(-1, 'execution')) && p() && e('0'); // 测试获取id为-1的任务的execution字段
r($task->fetchByID(-1, 'name'))      && p() && e('0'); // 测试获取id为-1的任务的name字段
r($task->fetchByID(-1, 'type'))      && p() && e('0'); // 测试获取id为-1的任务的type字段
r($task->fetchByID(-1, 'pri'))       && p() && e('0'); // 测试获取id为-1的任务的pri字段
r($task->fetchByID(-1, 'estimate'))  && p() && e('0'); // 测试获取id为-1的任务的estimate字段
r($task->fetchByID(-1, 'consumed'))  && p() && e('0'); // 测试获取id为-1的任务的consumed字段
r($task->fetchByID(-1, 'status'))    && p() && e('0'); // 测试获取id为-1的任务的status字段

r($task->fetchByID(0, 'id'))        && p() && e('0'); // 测试获取id为0的任务的id字段
r($task->fetchByID(0, 'project'))   && p() && e('0'); // 测试获取id为0的任务的project字段
r($task->fetchByID(0, 'execution')) && p() && e('0'); // 测试获取id为0的任务的execution字段
r($task->fetchByID(0, 'name'))      && p() && e('0'); // 测试获取id为0的任务的name字段
r($task->fetchByID(0, 'type'))      && p() && e('0'); // 测试获取id为0的任务的type字段
r($task->fetchByID(0, 'pri'))       && p() && e('0'); // 测试获取id为0的任务的pri字段
r($task->fetchByID(0, 'estimate'))  && p() && e('0'); // 测试获取id为0的任务的estimate字段
r($task->fetchByID(0, 'consumed'))  && p() && e('0'); // 测试获取id为0的任务的consumed字段
r($task->fetchByID(0, 'status'))    && p() && e('0'); // 测试获取id为0的任务的status字段

r($task->fetchByID(1, 'id'))        && p() && e('1');          // 测试获取id为1的任务的id字段
r($task->fetchByID(1, 'project'))   && p() && e('11');         // 测试获取id为1的任务的project字段
r($task->fetchByID(1, 'execution')) && p() && e('101');        // 测试获取id为1的任务的execution字段
r($task->fetchByID(1, 'name'))      && p() && e('开发任务11'); // 测试获取id为1的任务的name字段
r($task->fetchByID(1, 'type'))      && p() && e('design');     // 测试获取id为1的任务的type字段
r($task->fetchByID(1, 'pri'))       && p() && e('1');          // 测试获取id为1的任务的pri字段
r($task->fetchByID(1, 'estimate'))  && p() && e('0');          // 测试获取id为1的任务的estimate字段
r($task->fetchByID(1, 'consumed'))  && p() && e('3');          // 测试获取id为1的任务的consumed字段
r($task->fetchByID(1, 'status'))    && p() && e('wait');       // 测试获取id为1的任务的status字段

r($task->fetchByID(2, 'id'))        && p() && e('2');          // 测试获取id为2的任务的id字段
r($task->fetchByID(2, 'project'))   && p() && e('12');         // 测试获取id为2的任务的project字段
r($task->fetchByID(2, 'execution')) && p() && e('102');        // 测试获取id为2的任务的execution字段
r($task->fetchByID(2, 'name'))      && p() && e('开发任务12'); // 测试获取id为2的任务的name字段
r($task->fetchByID(2, 'type'))      && p() && e('devel');      // 测试获取id为2的任务的type字段
r($task->fetchByID(2, 'pri'))       && p() && e('2');          // 测试获取id为2的任务的pri字段
r($task->fetchByID(2, 'estimate'))  && p() && e('1');          // 测试获取id为2的任务的estimate字段
r($task->fetchByID(2, 'consumed'))  && p() && e('4');          // 测试获取id为2的任务的consumed字段
r($task->fetchByID(2, 'status'))    && p() && e('doing');      // 测试获取id为2的任务的status字段

r($task->fetchByID(3, 'id'))        && p() && e('3');          // 测试获取id为3的任务的id字段
r($task->fetchByID(3, 'project'))   && p() && e('13');         // 测试获取id为3的任务的project字段
r($task->fetchByID(3, 'execution')) && p() && e('103');        // 测试获取id为3的任务的execution字段
r($task->fetchByID(3, 'name'))      && p() && e('开发任务13'); // 测试获取id为3的任务的name字段
r($task->fetchByID(3, 'type'))      && p() && e('test');       // 测试获取id为3的任务的type字段
r($task->fetchByID(3, 'pri'))       && p() && e('3');          // 测试获取id为3的任务的pri字段
r($task->fetchByID(3, 'estimate'))  && p() && e('2');          // 测试获取id为3的任务的estimate字段
r($task->fetchByID(3, 'consumed'))  && p() && e('5');          // 测试获取id为3的任务的consumed字段
r($task->fetchByID(3, 'status'))    && p() && e('done');       // 测试获取id为3的任务的status字段

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->getById();
cid=1
pid=1

获取id为1的todo信息 >> 自定义1的待办,custom,wait
获取id为2的todo信息 >> BUG1,bug,doing
获取id为3的todo信息 >> 开发任务12,task,done
获取id为4的todo信息 >> 用户需求3,story,closed
获取id不存在的todo信息 >> 0

*/

$todoIDList = array('1', '2', '3', '4', '100001');

$todo = new todoTest();

r($todo->getByIdTest($todoIDList[0])) && p('name,type,status') && e('自定义1的待办,custom,wait'); // 获取id为1的todo信息
r($todo->getByIdTest($todoIDList[1])) && p('name,type,status') && e('BUG1,bug,doing');            // 获取id为2的todo信息
r($todo->getByIdTest($todoIDList[2])) && p('name,type,status') && e('开发任务12,task,done');      // 获取id为3的todo信息
r($todo->getByIdTest($todoIDList[3])) && p('name,type,status') && e('用户需求3,story,closed');    // 获取id为4的todo信息
r($todo->getByIdTest($todoIDList[4])) && p('name,type,status') && e('0');                         // 获取id不存在的todo信息
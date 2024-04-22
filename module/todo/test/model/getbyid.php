#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData ()
{
    zenData('todo')->loadYaml('getbyid')->gen(5);
}

/**

title=测试 todoModel->getByID();
cid=1
pid=0
*/

$todoIDList = array('1', '2', '3', '5', '100000');

$todo = new todoTest();

initData();

r($todo->getByIDTest($todoIDList[0])) && p('name,status') && e('自定义的待办1,wait'); // 获取id为1的todo信息
r($todo->getByIDTest($todoIDList[1])) && p('name,status') && e('自定义的待办2,doing'); // 获取id为2的todo信息
r($todo->getByIDTest($todoIDList[3])) && p('name,status') && e('自定义的待办5,closed'); // 获取id为5的todo信息
r($todo->getByIDTest($todoIDList[4])) && p() && e('0'); // 获取不存在的id todo信息

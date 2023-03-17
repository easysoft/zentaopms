#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->update();
cid=1
pid=1

测试更新todo名称 >> name,自定义1的待办,john
测试更新todo类型 >> type,custom,bug
测试更新todo名称和类型 >> type,bug,custom;name,BUG2的待办,jack
测试不更新todo任何数据 >> 没有数据更新

*/

$todoIDList = array('1', '2');

$t_upname    = array('name' => 'john');
$t_uptype    = array('type' => 'bug', 'idvalue' => '1');
$t_typename  = array('name' => 'jack', 'type' => 'custom');
$t_unname    = array('name' => 'john');

$todo = new todoTest();

r($todo->updateTest($todoIDList[0], $t_upname))   && p('0:field,old,new')                 && e('name,自定义1的待办,john');              // 测试更新todo名称
r($todo->updateTest($todoIDList[0], $t_uptype))   && p('0:field,old,new')                 && e('type,custom,bug');                      // 测试更新todo类型
r($todo->updateTest($todoIDList[1], $t_typename)) && p('0:field,old,new;1:field,old,new') && e('type,bug,custom;name,BUG2的待办,jack'); // 测试更新todo名称和类型
r($todo->updateTest($todoIDList[0], $t_unname))   && p()                                  && e('没有数据更新');                         // 测试不更新todo任何数据

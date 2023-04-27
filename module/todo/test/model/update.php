#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    $todo = zdTable('todo');
    $todo->id->range('1');
    $todo->account->range('admin');
    $todo->date->range(date('Y-m-d'));
    $todo->begin->range('1000');
    $todo->end->range('2400');
    $todo->type->range('custom');
    $todo->name->range('这是一个待办');
    $todo->status->range('wait');
    $todo->vision->range('rnd');

    $todo->gen(1);
}

/**

title=测试 todoModel::update;
timeout=0
cid=1

- 执行todo模块的update方法，参数是1, $t_upname
 - 第0条的field属性 @name
 - 第0条的old属性 @这是一个待办
 - 第0条的new属性 @john

- 执行todo模块的update方法，参数是1, $t_uptype
 - 第0条的field属性 @type
 - 第0条的old属性 @custom
 - 第0条的new属性 @bug

- 执行todo模块的update方法，参数是1, $t_unname @没有数据更新

*/

global $tester;
$tester->loadModel('todo');

initData();

$t_upname = array('name' => 'john');
$t_uptype = array('type' => 'bug', 'idvalue' => '1');
$t_unname = array('name' => 'john');

$todo = new todoTest();
r($todo->updateTest(1, $t_upname)) && p('0:field,old,new') && e('name,这是一个待办,john');
r($todo->updateTest(1, $t_uptype)) && p('0:field,old,new') && e('type,custom,bug');
r($todo->updateTest(1, $t_unname)) && p()                  && e('没有数据更新');
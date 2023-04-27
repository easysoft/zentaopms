#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

/**

title=测试 todoModel->close();
cid=1
pid=1

*/
function initData()
{
    $todo = zdTable('todo');
    $todo->id->range('1');
    $todo->name->prefix("待办")->range('1');
    $todo->date->range('`2023-04-23`');
    $todo->type->range('custom');
    $todo->status->range('wait');
    $todo->gen(1, '', true);
}

initData();

global $tester;
$tester->loadModel('todo');


r($tester->todo->getByID(1)) && p('status') && e('wait');
r($tester->todo->close(1)) && p() && e(1);
r($tester->todo->getByID(1)) && p('status') && e('closed');

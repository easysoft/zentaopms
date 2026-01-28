#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testtask')->gen(10);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testtaskModel->update();
cid=19220
pid=1

- 测试修改测试单 name
 - 属性name @修改name
 - 属性status @wait
 - 属性product @1
- 测试修改测试单 status
 - 属性name @测试单2
 - 属性status @doing
 - 属性product @2
- 测试修改测试单 product
 - 属性name @测试单3
 - 属性status @done
 - 属性product @10
- 测试修改测试单 name status product
 - 属性name @修改name
 - 属性status @doing
 - 属性product @10

*/

$task1 = new stdclass();
$task1->id   = 1;
$task1->name = '修改name';

$task2         = new stdclass();
$task2->id     = 2;
$task2->status = 'doing';

$task3          = new stdclass();
$task3->id      = 3;
$task3->product = '10';

$task4          = new stdclass();
$task4->id      = 4;
$task4->name    = '修改name';
$task4->status  = 'doing';
$task4->product = '10';

$testtask = new testtaskModelTest();

r($testtask->updateTest($task1)) && p('name,status,product') && e('修改name,wait,1');  // 测试修改测试单 name
r($testtask->updateTest($task2)) && p('name,status,product') && e('测试单2,doing,2');   // 测试修改测试单 status
r($testtask->updateTest($task3)) && p('name,status,product') && e('测试单3,done,10');  // 测试修改测试单 product
r($testtask->updateTest($task4)) && p('name,status,product') && e('修改name,doing,10'); // 测试修改测试单 name status product

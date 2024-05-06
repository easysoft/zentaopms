#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zenData('todo')->loadYaml('getlistby')->gen(5);

/**

title=测试 todoModel->getListBy();
cid=1
pid=0

*/

global $tester;
$tester->loadModel('todo')->todoTao;


$type    = 'before'; // assignedtoother, cycle
$account = 'admin';
$status  = 'all'; // done,closed,wait,doing
$begin   = '2021-02-03';
$end     = '2024-04-04';
$limit   = 5;
$orderBy = 'date_desc';

$result = $tester->todo->getListBy($type, $account, $status, $begin, $end, $limit, $orderBy);

r(count($result)) && p() && e('5'); //获取待办列表数量
r($result[0]) && p('name,status') && e('待办5,doing'); //获取待办列表第1条的name和status

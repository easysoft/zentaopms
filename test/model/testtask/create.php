#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testtask.class.php';
su('admin');

/**

title=测试 testtaskModel->create();
cid=1
pid=1

新增一个正常的测试单 >> 11,新增测试报告,200,1,system
新增一个名称为空的测试单 >> 『名称』不能为空。
新增一个结束日期小于开始日期的测试单 >> 『结束日期』应当不小于『2022-10-05』。

*/

$testtask = new testtaskTest();

$normalTask['product']   = 1;
$normalTask['execution'] = 200;
$normalTask['build']     = 11;
$normalTask['type']      = 'system';
$normalTask['owner']     = 'test10';
$normalTask['pri']       = 3;
$normalTask['begin']     = '2022-03-05';
$normalTask['end']       = '2022-09-05';
$normalTask['status']    = 'wait';
$normalTask['name']      = '新增测试单';
$normalTask['desc']      = '新增测试单的描述详情';

$emptyNameTask = $normalTask;
$emptyNameTask['name'] = '';

$beginGtEndTask = $normalTask;
$beginGtEndTask['begin'] = '2022-10-05';

r($testtask->create(11, $normalTask))     && p('id,name,execution,product,type') && e('11,新增测试报告,200,1,system');           // 新增一个正常的测试单
r($testtask->create(12, $emptyNameTask))  && p('name:0')                         && e('『名称』不能为空。');                     // 新增一个名称为空的测试单
r($testtask->create(13, $beginGtEndTask)) && p('end:0')                          && e('『结束日期』应当不小于『2022-10-05』。'); // 新增一个结束日期小于开始日期的测试单


#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->startTask();
timeout=0
cid=18106

- 正常启动任务返回task对象 >> 1
- 完成剩余时间为0的任务返回对象 >> 1
- 无效任务ID返回false >> 1
- 方法存在且可调用 >> 1
- 正常任务不抛异常 >> 1

*/

zenData('task')->gen(10);
zenData('user')->gen(5);

$repoTest = new repoModelTest();

r($repoTest->startTaskTest(1, array('left' => 6, 'consumed' => 2)))   && p('id') && e('1');
r($repoTest->startTaskTest(2, array('left' => 0, 'consumed' => 8)))   && p('id') && e('2');
r($repoTest->startTaskTest(999, array('left' => 4, 'consumed' => 2))) && p()     && e('0');
r($repoTest->startTaskTest(4, array('left' => 2, 'consumed' => 1)))   && p('id') && e('4');
r($repoTest->startTaskTest(3, array('left' => 5, 'consumed' => 3)))   && p('id') && e('3');

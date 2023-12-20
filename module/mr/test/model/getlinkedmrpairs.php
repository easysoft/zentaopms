#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getLinkedMRPairs();
timeout=0
cid=0

- 查询ID为1的需求关联的MR列表属性1 @test-merge
- 查询ID为1的任务关联的MR列表 @0
- 查询ID为1的Bug关联的MR列表 @0
- 查询ID为2的任务关联的MR列表属性1 @test-merge
- 查询ID为3的Bug关联的MR列表属性1 @test-merge
- 错误的类型 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('mr')->config('mr')->gen(10);
zdTable('relation')->config('relation')->gen(10);
su('admin');

global $tester;
$mrModel = $tester->loadModel('mr');

/* Object id and type is right.  */
r($mrModel->getLinkedMRPairs(1, 'story')) && p('1') && e('test-merge'); // 查询ID为1的需求关联的MR列表

r($mrModel->getLinkedMRPairs(1, 'task')) && p() && e('0'); // 查询ID为1的任务关联的MR列表
r($mrModel->getLinkedMRPairs(1, 'bug')) && p() && e('0'); // 查询ID为1的Bug关联的MR列表

r($mrModel->getLinkedMRPairs(2, 'task')) && p('1') && e('test-merge'); // 查询ID为2的任务关联的MR列表

r($mrModel->getLinkedMRPairs(3, 'bug')) && p('1') && e('test-merge'); // 查询ID为3的Bug关联的MR列表

/* Type is wrong. */
r($mrModel->getLinkedMRPairs(1, 'story1')) && p() && e('0'); // 错误的类型
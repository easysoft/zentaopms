#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getLinkedMRPairs();
timeout=0
cid=17249

- 查询ID为1的需求关联的MR列表
 - 第1条的title属性 @test-merge
 - 第1条的status属性 @opened
- 查询ID为1的任务关联的MR列表 @0
- 查询ID为1的Bug关联的MR列表 @0
- 查询ID为2的任务关联的MR列表
 - 第1条的title属性 @test-merge
 - 第1条的status属性 @opened
- 查询ID为3的Bug关联的MR列表
 - 第1条的title属性 @test-merge
 - 第1条的status属性 @opened
- 错误的类型 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('mr')->loadYaml('mr')->gen(10);
zenData('relation')->loadYaml('relation')->gen(10);
su('admin');

global $tester;
$mrModel = $tester->loadModel('mr');

/* Object id and type is right.  */
r($mrModel->getLinkedMRPairs(1, 'story')) && p('1:title,status') && e('test-merge,opened'); // 查询ID为1的需求关联的MR列表

r($mrModel->getLinkedMRPairs(1, 'task')) && p() && e('0'); // 查询ID为1的任务关联的MR列表
r($mrModel->getLinkedMRPairs(1, 'bug')) && p() && e('0'); // 查询ID为1的Bug关联的MR列表

r($mrModel->getLinkedMRPairs(2, 'task')) && p('1:title,status') && e('test-merge,opened'); // 查询ID为2的任务关联的MR列表

r($mrModel->getLinkedMRPairs(3, 'bug')) && p('1:title,status') && e('test-merge,opened'); // 查询ID为3的Bug关联的MR列表

/* Type is wrong. */
r($mrModel->getLinkedMRPairs(1, 'story1')) && p() && e('0'); // 错误的类型
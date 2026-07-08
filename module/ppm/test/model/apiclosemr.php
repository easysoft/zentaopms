#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCloseMR();
timeout=0
cid=17222

- 测试步骤1：不存在的主机ID @0
- 测试步骤2：有效的Gitlab主机关闭MR
 - 属性title @test
 - 属性state @closed
- 测试步骤3：再次关闭同一个MR
 - 属性title @test
 - 属性state @closed
- 测试步骤4：使用不同项目ID关闭MR
 - 属性message @404 Not found
- 测试步骤5：不存在主机和项目的组合 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);
su('admin');

$mrModel = new mrModelTest();

r($mrModel->apiCloseMRTest(10, '3', 114)) && p() && e('0'); // 测试步骤1：不存在的主机ID
r($mrModel->apiCloseMRTest(1, '3', 114)) && p('title,state') && e('test,closed'); // 测试步骤2：有效的Gitlab主机关闭MR
r($mrModel->apiCloseMRTest(1, '3', 114)) && p('title,state') && e('test,closed'); // 测试步骤3：再次关闭同一个MR
r($mrModel->apiCloseMRTest(1, '4', 114)) && p('message') && e('404 Not found'); // 测试步骤4：使用不同项目ID关闭MR
r($mrModel->apiCloseMRTest(10, '4', 115)) && p() && e('0'); // 测试步骤5：不存在主机和项目的组合

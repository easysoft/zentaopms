#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::updateRelated();
timeout=0
cid=0

- 步骤1：整数ID输入 @rue
- 步骤2：字符串ID列表输入 @rue
- 步骤3：数组ID列表输入 @rue
- 步骤4：空值输入 @alse
- 步骤5：无效对象类型 @rue
- 步骤6：重复ID处理 @rue
- 步骤7：混合格式处理 @rue

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('release')->gen(0);
zenData('releaserelated')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$releaseTest = new releaseTest();

// 5. 执行测试步骤
r($releaseTest->updateRelatedTest(1, 'story', 101)) && p() && e(true); // 步骤1：整数ID输入
r($releaseTest->updateRelatedTest(2, 'bug', '201,202,203')) && p() && e(true); // 步骤2：字符串ID列表输入
r($releaseTest->updateRelatedTest(3, 'project', array(301, 302, 303))) && p() && e(true); // 步骤3：数组ID列表输入
r($releaseTest->updateRelatedTest(4, 'story', '')) && p() && e(false); // 步骤4：空值输入
r($releaseTest->updateRelatedTest(5, 'invalidtype', 501)) && p() && e(true); // 步骤5：无效对象类型
r($releaseTest->updateRelatedTest(1, 'build', '601,601,602,602')) && p() && e(true); // 步骤6：重复ID处理
r($releaseTest->updateRelatedTest(2, 'story', '701,702,703')) && p() && e(true); // 步骤7：混合格式处理
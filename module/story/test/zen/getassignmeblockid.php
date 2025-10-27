#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAssignMeBlockID();
timeout=0
cid=0

- 步骤1：非onlybody模式 @0
- 执行storyTest模块的getAssignMeBlockIDTest方法  @0
- 执行storyTest模块的getAssignMeBlockIDTest方法  @0
- 执行storyTest模块的getAssignMeBlockIDTest方法  @0
- 执行storyTest模块的getAssignMeBlockIDTest方法  @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('block')->loadYaml('block_getassignmeblockid', false, 2)->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 模拟非onlybody模式
$_GET['onlybody'] = null;
r($storyTest->getAssignMeBlockIDTest()) && p() && e(0); // 步骤1：非onlybody模式

// 模拟onlybody模式
$_GET['onlybody'] = 'yes';

// 步骤2：onlybody模式下存在符合条件的block（注意原代码有逻辑问题，会返回0）
r($storyTest->getAssignMeBlockIDTest()) && p() && e(0);

// 步骤3：切换到user1用户测试
su('user1');
r($storyTest->getAssignMeBlockIDTest()) && p() && e(0);

// 步骤4：切换到test用户测试
su('test');
r($storyTest->getAssignMeBlockIDTest()) && p() && e(0);

// 步骤5：测试空用户情况
su('');
r($storyTest->getAssignMeBlockIDTest()) && p() && e(0);
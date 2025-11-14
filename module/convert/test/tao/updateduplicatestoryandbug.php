#!/usr/bin/env php
<?php

/**

title=测试 convertTao::updateDuplicateStoryAndBug();
timeout=0
cid=15873

- 步骤1：正常需求重复关联 @1
- 步骤2：正常缺陷重复关联 @1
- 步骤3：空重复关联数据 @1
- 步骤4：不匹配的对象类型 @1
- 步骤5：不一致的对象类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求{1-10}');
$story->type->range('story{10}');
$story->status->range('active{10}');
$story->duplicateStory->range('0{10}');
$story->gen(10);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->title->range('缺陷{1-10}');
$bug->status->range('active{10}');
$bug->duplicateBug->range('0{10}');
$bug->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->updateDuplicateStoryAndBugTest(array('1' => '2'), array('1' => array('BType' => 'zstory', 'BID' => '1'), '2' => array('BType' => 'zstory', 'BID' => '2')))) && p() && e('1'); // 步骤1：正常需求重复关联
r($convertTest->updateDuplicateStoryAndBugTest(array('1' => '2'), array('1' => array('BType' => 'zbug', 'BID' => '1'), '2' => array('BType' => 'zbug', 'BID' => '2')))) && p() && e('1'); // 步骤2：正常缺陷重复关联
r($convertTest->updateDuplicateStoryAndBugTest(array(), array('1' => array('BType' => 'zstory', 'BID' => '1')))) && p() && e('1'); // 步骤3：空重复关联数据
r($convertTest->updateDuplicateStoryAndBugTest(array('1' => '2'), array('1' => array('BType' => 'ztask', 'BID' => '1'), '2' => array('BType' => 'ztask', 'BID' => '2')))) && p() && e('1'); // 步骤4：不匹配的对象类型
r($convertTest->updateDuplicateStoryAndBugTest(array('1' => '2'), array('1' => array('BType' => 'zstory', 'BID' => '1'), '2' => array('BType' => 'zbug', 'BID' => '2')))) && p() && e('1'); // 步骤5：不一致的对象类型
#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerGrade();
timeout=0
cid=18516

- 步骤1：测试story类型需求按等级统计，验证第一个等级数据第0条的value属性 @6
- 步骤2：验证第二个等级数据第1条的value属性 @6
- 步骤3：验证第三个等级数据第2条的value属性 @8
- 步骤4：验证story类型返回3个等级统计 @3
- 步骤5：验证requirement类型返回3个等级统计 @3
- 步骤6：验证epic类型返回3个等级统计 @3
- 步骤7：验证不存在类型也返回统计结果 @3
- 步骤8：验证name字段显示为未定义第0条的name属性 @未定义

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$storyTable = zenData('story');
$storyTable->type->range('story{8},requirement{6},epic{6}');
$storyTable->grade->range('1{8},2{6},3{6}');
$storyTable->deleted->range('0{18},1{2}');
$storyTable->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 设置语言变量以避免undefined错误
global $lang;
if(!isset($lang->report)) $lang->report = new stdclass();
if(!isset($lang->report->undefined)) $lang->report->undefined = '未定义';

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyTest->getDataOfStoriesPerGradeTest('story')) && p('0:value') && e('6'); // 步骤1：测试story类型需求按等级统计，验证第一个等级数据
r($storyTest->getDataOfStoriesPerGradeTest('story')) && p('1:value') && e('6'); // 步骤2：验证第二个等级数据
r($storyTest->getDataOfStoriesPerGradeTest('story')) && p('2:value') && e('8'); // 步骤3：验证第三个等级数据
r(count($storyTest->getDataOfStoriesPerGradeTest('story'))) && p() && e('3'); // 步骤4：验证story类型返回3个等级统计
r(count($storyTest->getDataOfStoriesPerGradeTest('requirement'))) && p() && e('3'); // 步骤5：验证requirement类型返回3个等级统计
r(count($storyTest->getDataOfStoriesPerGradeTest('epic'))) && p() && e('3'); // 步骤6：验证epic类型返回3个等级统计
r(count($storyTest->getDataOfStoriesPerGradeTest('notexist'))) && p() && e('3'); // 步骤7：验证不存在类型也返回统计结果
r($storyTest->getDataOfStoriesPerGradeTest('story')) && p('0:name') && e('未定义'); // 步骤8：验证name字段显示为未定义
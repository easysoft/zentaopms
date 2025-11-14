#!/usr/bin/env php
<?php

/**

title=测试 markModel::setMark();
timeout=0
cid=17047

- 步骤1：正常设置单个对象标记 @0
- 步骤2：批量设置多个对象标记 @0
- 步骤3：设置相同对象的不同版本标记 @0
- 步骤4：设置不同类型的标记 @0
- 步骤5：设置包含额外信息的标记 @0
- 步骤6：设置空对象ID数组 @0
- 步骤7：设置重复对象标记 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mark.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$markTable = zenData('mark');
$markTable->id->range('1-1000');
$markTable->objectType->range('story{10},task{10},bug{10}');
$markTable->objectID->range('1-30');
$markTable->version->range('1.0{5},2.0{5},all{10}');
$markTable->account->range('admin{15},user{15}');
$markTable->mark->range('view{20},favorite{10}');
$markTable->extra->range('[]{20},note1{5},note2{5}');
$markTable->gen(0); // 先生成0条记录，从空开始测试

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$markTest = new markTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($markTest->setMarkTest([1], 'story', '1.0', 'view', '')) && p() && e('0'); // 步骤1：正常设置单个对象标记
r($markTest->setMarkTest([1, 2, 3], 'task', '1.0', 'view', '')) && p() && e('0'); // 步骤2：批量设置多个对象标记
r($markTest->setMarkTest([1], 'story', '2.0', 'view', '')) && p() && e('0'); // 步骤3：设置相同对象的不同版本标记
r($markTest->setMarkTest([4], 'bug', '1.0', 'favorite', '')) && p() && e('0'); // 步骤4：设置不同类型的标记
r($markTest->setMarkTest([5], 'story', '1.0', 'view', 'important')) && p() && e('0'); // 步骤5：设置包含额外信息的标记
r($markTest->setMarkTest([], 'story', '1.0', 'view', '')) && p() && e('0'); // 步骤6：设置空对象ID数组
r($markTest->setMarkTest([1], 'story', '1.0', 'view', '')) && p() && e('0'); // 步骤7：设置重复对象标记
#!/usr/bin/env php
<?php

/**

title=测试 storyModel::get2BeClosed();
timeout=0
cid=18495

- 正常情况测试 @1
- 多产品ID测试 @1
- 无效产品ID测试 @0
- 分支过滤测试 @0
- 需求类型过滤测试 @0
- 详细信息测试
 - 第10条的title属性 @软件需求10
 - 第10条的type属性 @story
 - 第10条的stage属性 @developed
- 空数组产品ID测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据：使用简化的数据配置
zenData('story')->gen(50);

su('admin');

$storyTest = new storyModelTest();

// 测试步骤1：正常获取产品下需要关闭的需求数量
r(count($storyTest->get2BeClosedTest(3, 0, '', 'story', 'id_desc'))) && p() && e('1'); // 正常情况测试

// 测试步骤2：测试多个产品ID的情况数量
r(count($storyTest->get2BeClosedTest(array(1, 2, 3), 0, '', 'story'))) && p() && e('1'); // 多产品ID测试

// 测试步骤3：测试无效产品ID参数
r(count($storyTest->get2BeClosedTest(999, 0, '', 'story'))) && p() && e('0'); // 无效产品ID测试

// 测试步骤4：测试不同分支和模块过滤
r(count($storyTest->get2BeClosedTest(1, 'all', array(), 'story'))) && p() && e('0'); // 分支过滤测试

// 测试步骤5：测试不同需求类型过滤
r(count($storyTest->get2BeClosedTest(array(1, 2), 0, '', 'requirement'))) && p() && e('0'); // 需求类型过滤测试

// 测试步骤6：测试产品3下developed状态story的详细信息
r($storyTest->get2BeClosedTest(3, 0, '', 'story', 'id_desc')) && p('10:title,type,stage') && e('软件需求10,story,developed'); // 详细信息测试

// 测试步骤7：测试边界值场景
r(count($storyTest->get2BeClosedTest(array(), 0, '', 'story'))) && p() && e('0'); // 空数组产品ID测试
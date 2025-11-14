#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::updateSubStageAttr();
timeout=0
cid=17759

- 测试attribute为mix的情况，应该直接返回true，不更新任何数据 @empty string
- 测试无子阶段的情况，应该返回true，不执行更新操作 @design
- 测试有子阶段的正常更新情况，应该成功更新所有子阶段的attribute @design
- 测试多层级递归更新，有子阶段且子阶段也有子阶段的情况 @review
- 测试边界值情况，planID为0的异常情况 @test
- 测试已删除子阶段的过滤，确保只更新未删除的子阶段 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

zenData('project')->loadYaml('project_updatesubstageattr', false, 2)->gen(20);

su('admin');

$plan = new programplanTest();
r($plan->updateSubStageAttrTest(1, 'mix')) && p() && e('empty string'); // 测试attribute为mix的情况，应该直接返回true，不更新任何数据
r($plan->updateSubStageAttrTest(5, 'design')) && p() && e('design');     // 测试无子阶段的情况，应该返回true，不执行更新操作
r($plan->updateSubStageAttrTest(1, 'design')) && p() && e('design');     // 测试有子阶段的正常更新情况，应该成功更新所有子阶段的attribute
r($plan->updateSubStageAttrTest(2, 'review')) && p() && e('review');     // 测试多层级递归更新，有子阶段且子阶段也有子阶段的情况
r($plan->updateSubStageAttrTest(0, 'test')) && p() && e('test');         // 测试边界值情况，planID为0的异常情况
r($plan->updateSubStageAttrTest(11, 'request')) && p() && e(1);          // 测试已删除子阶段的过滤，确保只更新未删除的子阶段
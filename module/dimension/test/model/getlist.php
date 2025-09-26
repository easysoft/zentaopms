#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dimension.unittest.class.php';

$table = zenData('dimension');
$table->id->range('1-10');
$table->name->range('宏观管理维度,效能管理维度,质量管理维度,财务管理维度,人力资源维度,风险控制维度,客户服务维度,技术创新维度,运营效率维度,市场营销维度');
$table->code->range('macro,efficiency,quality,finance,hr,risk,service,tech,operation,marketing');
$table->desc->range('为管理层提供洞察力和决策支持,识别项目管理流程中的关键步骤,确保项目交付过程和成果符合质量标准,财务数据分析和预算管理,人力资源管理和团队建设,风险评估和控制措施,客户满意度和服务质量,技术创新和研发管理,运营流程优化和效率提升,市场分析和营销策略');
$table->createdBy->range('admin{6},system{4}');
$table->createdDate->range('`2023-01-01 10:00:00`,`2023-01-02 10:00:00`,`2023-01-03 10:00:00`,`2023-01-04 10:00:00`,`2023-01-05 10:00:00`,`2023-01-06 10:00:00`,`2023-01-07 10:00:00`,`2023-01-08 10:00:00`,`2023-01-09 10:00:00`,`2023-01-10 10:00:00`');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

/**

title=测试dimensionModel->getList();
timeout=0
cid=1

- 正常获取维度列表
 - 第1条的name属性 @宏观管理维度
 - 第1条的code属性 @macro
- 验证第二条数据第2条的name属性 @效能管理维度
- 验证第三条数据第3条的code属性 @quality
- 验证id键值第1条的id属性 @1
- 验证返回数组数量 @8

*/

$dimensionTester = new dimensionTest();
r($dimensionTester->getListTest()) && p('1:name,code')   && e('宏观管理维度,macro');  // 正常获取维度列表
r($dimensionTester->getListTest()) && p('2:name')        && e('效能管理维度');        // 验证第二条数据
r($dimensionTester->getListTest()) && p('3:code')        && e('quality');           // 验证第三条数据
r($dimensionTester->getListTest()) && p('1:id')          && e('1');                // 验证id键值
r($dimensionTester->getListTestWithCount()) && p()       && e('8');                // 验证返回数组数量
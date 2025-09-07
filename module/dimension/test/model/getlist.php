#!/usr/bin/env php
<?php

/**

title=测试 dimensionModel::getList();
timeout=0
cid=0

- 执行dimensionTest模块的getListTest方法，参数是'1, 2, 3, 4, 5', 'count'  @5
- 执行dimensionTest模块的getListTest方法，参数是'1, 3, 5' 第1条的name属性 @宏观管理维度
- 执行dimensionTest模块的getListTest方法，参数是'1, 3, 5' 第3条的code属性 @quality
- 执行dimensionTest模块的getListTest方法，参数是'', 'count'  @0
- 执行dimensionTest模块的getListTest方法，参数是'1, 2, 3', 'count'  @3

*/

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

su('admin');

$dimensionTest = new dimensionTest();

r($dimensionTest->getListTest('1,2,3,4,5', 'count')) && p() && e('5');
r($dimensionTest->getListTest('1,3,5')) && p('1:name') && e('宏观管理维度');
r($dimensionTest->getListTest('1,3,5')) && p('3:code') && e('quality');
r($dimensionTest->getListTest('', 'count')) && p() && e('0');
r($dimensionTest->getListTest('1,2,3', 'count')) && p() && e('3');
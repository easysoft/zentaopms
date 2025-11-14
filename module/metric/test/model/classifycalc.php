#!/usr/bin/env php
<?php

/**

title=测试 metricModel::classifyCalc();
timeout=0
cid=17070

- 执行metricTest模块的classifyCalcTest方法，参数是$calcList1 第0条的dataset属性 @user_dataset
- 执行metricTest模块的classifyCalcTest方法，参数是array  @0
- 执行metricTest模块的classifyCalcTest方法，参数是$calcList3 第0条的dataset属性 @~~
- 执行metricTest模块的classifyCalcTest方法，参数是$calcList4 第0条的dataset属性 @project_dataset
- 执行metricTest模块的classifyCalcTest方法，参数是$calcList5 第1条的dataset属性 @story_dataset

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

// 测试步骤1：正常输入，有相同dataset的计算器归类
$calcList1 = array();
$calc1 = new stdClass();
$calc1->dataset = 'user_dataset';
$calc1->code = 'user_count';
$calcList1['user_count'] = $calc1;

$calc2 = new stdClass();
$calc2->dataset = 'user_dataset';
$calc2->code = 'user_active';
$calcList1['user_active'] = $calc2;

r($metricTest->classifyCalcTest($calcList1)) && p('0:dataset') && e('user_dataset');

// 测试步骤2：边界值输入，空数组
r($metricTest->classifyCalcTest(array())) && p() && e(0);

// 测试步骤3：无效输入，所有计算器都没有dataset
$calcList3 = array();
$calc3 = new stdClass();
$calc3->code = 'no_dataset1';
$calcList3['no_dataset1'] = $calc3;

$calc4 = new stdClass();
$calc4->code = 'no_dataset2';
$calcList3['no_dataset2'] = $calc4;

r($metricTest->classifyCalcTest($calcList3)) && p('0:dataset') && e('~~');

// 测试步骤4：混合输入，部分有dataset部分没有
$calcList4 = array();
$calc5 = new stdClass();
$calc5->dataset = 'project_dataset';
$calc5->code = 'project_count';
$calcList4['project_count'] = $calc5;

$calc6 = new stdClass();
$calc6->code = 'no_dataset3';
$calcList4['no_dataset3'] = $calc6;

r($metricTest->classifyCalcTest($calcList4)) && p('0:dataset') && e('project_dataset');

// 测试步骤5：多个不同dataset
$calcList5 = array();
$calc7 = new stdClass();
$calc7->dataset = 'bug_dataset';
$calc7->code = 'bug_count';
$calcList5['bug_count'] = $calc7;

$calc8 = new stdClass();
$calc8->dataset = 'story_dataset';
$calc8->code = 'story_count';
$calcList5['story_count'] = $calc8;

r($metricTest->classifyCalcTest($calcList5)) && p('1:dataset') && e('story_dataset');
#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildSelect();
timeout=0
cid=1

- 判断生成的年份过滤条件是否正确第testChart条的year属性 @testField
- 判断不传入年份的情况下，是否生成了默认值。
 - 第0条的label属性 @2023
 - 第0条的value属性 @2023
- 判断最小时间小于2009年的情况下，时间标签是否截断在2009年。 @1
- 判断不传入部门的情况下，是否生成了所有部门的下拉菜单项。
 - 第0条的label属性 @所有部门
 - 第0条的value属性 @0
 - 第2条的label属性 @开发部2
 - 第2条的value属性 @2
- 判断生成的部门过滤条件是否正确第testChart条的dept属性 @testField
- 判断传入部门ID的情况下，生成的值是否正确。 @1
- 判断不传入用户的情况下，是否生成了所有用户的下拉菜单项。
 - 第0条的label属性 @所有用户
 - 第0条的value属性 @~~
 - 第11条的label属性 @用户10
 - 第11条的value属性 @user10
- 判断生成的用户过滤条件是否正确第testChart条的account属性 @testField
- 判断传入部门ID的情况下，是否生成了当前部门用户的下拉菜单项。
 - 第0条的label属性 @所有用户
 - 第0条的value属性 @~~
 - 第10条的label属性 @用户9
 - 第10条的value属性 @user9
- 判断传入用户ID的情况下，生成的值是否正确。 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

$screen = new screenTest();

$component1 = new stdclass();
$component1->option = new stdclass();
$component1->type = 'year';

$component1_ = clone($component1);

$component2 = new stdclass();
$component2->option = new stdclass();
$component2->type = 'dept';

$component2_ = clone($component2);

$component3 = new stdclass();
$component3->option = new stdclass();
$component3->type = 'account';

$yearList    = array('', '2020', '2001');
$deptList    = array('0', '1');
$accountList = array('', 'user1');

function addFilterCharts($component)
{
    $filterCharts = new stdclass();
    $filterCharts->chart = 'testChart';
    $filterCharts->field = 'testField';
    $component->filterCharts = array($filterCharts);
}

addFilterCharts($component1);
$filter = $screen->buildSelectTest($component1, $yearList[0]);
r($filter->charts) && p('testChart:year') && e('testField');                //判断生成的年份过滤条件是否正确
r($component1->option->dataset) && p('0:label,value') && e('2023,2023');    //判断不传入年份的情况下，是否生成了默认值。

zdTable('action')->config('action')->gen(1);

addFilterCharts($component1_);
$filter = $screen->buildSelectTest($component1_, $yearList[1]);
$year = date('Y');
$labelList = $component1_->option->dataset;
$firstYear = current($labelList);
$endYear   = end($labelList);
r($firstYear['label'] == $year && $endYear['label'] == '2009') && p('') && e(1);   //判断最小时间小于2009年的情况下，时间标签是否截断在2009年。

zdTable('dept')->gen(2);
addFilterCharts($component2);
$filter = $screen->buildSelectTest($component2, $yearList[0], $deptList[0]);
r($component2->option->dataset) && p('0:label,value;2:label,value') && e('所有部门,0;开发部2,2');    //判断不传入部门的情况下，是否生成了所有部门的下拉菜单项。
r($filter->charts) && p('testChart:dept') && e('testField');    //判断生成的部门过滤条件是否正确

$screen->buildSelectTest($component2, $yearList[0], $deptList[1]);
r($component2->option->value) && p('') && e(1);    //判断传入部门ID的情况下，生成的值是否正确。

zdTable('user')->gen(11);
$screen->initFilter();
addFilterCharts($component3);
$filter = $screen->buildSelectTest($component3, $yearList[0], $deptList[0], $accountList[0]);
r($component3->option->dataset) && p('0:label,value;11:label,value') && e('所有用户,~~;用户10,user10');    //判断不传入用户的情况下，是否生成了所有用户的下拉菜单项。
r($filter->charts) && p('testChart:account') && e('testField');    //判断生成的用户过滤条件是否正确

$screen->initFilter();
$screen->buildSelectTest($component3, $yearList[0], $deptList[1], $accountList[1]);
r($component3->option->dataset) && p('0:label,value;10:label,value') && e('所有用户,~~;用户9,user9');    //判断传入部门ID的情况下，是否生成了当前部门用户的下拉菜单项。
r($component3->option->value) && p('') && e('user1');    //判断传入用户ID的情况下，生成的值是否正确。

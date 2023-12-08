#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getRows();
timeout=0
cid=1

- 测试筛选器为空，所属产品为组，以ID去重后计数为条件查询的需求数据
 - 第0条的product属性 @1
 - 第0条的id属性 @4
- 测试筛选ID小于3，所属产品为组，以ID去重后计数为条件查询的需求数据
 - 第0条的product属性 @1
 - 第0条的id属性 @2
- 测试筛选status等于active，所属产品为组，以ID去重后计数为条件查询的需求数据
 - 第1条的product属性 @2
 - 第1条的id属性 @1
- 测试筛选器为空，创建日期按月分组，以ID去重后计数为条件查询的需求数据
 - 第0条的ttyear属性 @2022
 - 第0条的ttgroup属性 @1
 - 第0条的id属性 @5
- 测试筛选器为空，创建日期按年分组，以ID去重后计数为条件查询的需求数据
 - 第0条的openedDate属性 @2022
 - 第0条的id属性 @25
- 测试筛选器为空，创建日期按周分组，以ID去重后计数为条件查询的需求数据
 - 第1条的openedDate属性 @202201
 - 第1条的id属性 @4
- 测试筛选器为空，创建日期按日为组，以ID去重后计数为条件查询的需求数据
 - 第2条的openedDate属性 @2022-01-03
 - 第2条的id属性 @1
- 测试筛选器为空，所属产品为组，以ID计数为条件查询的需求数据
 - 第0条的product属性 @1
 - 第0条的id属性 @4
- 测试筛选器为空，所属产品为组，以ID求和为条件查询的需求数据
 - 第0条的product属性 @1
 - 第0条的id属性 @10
- 测试筛选器为空，所属产品为组，以ID求平均值为条件查询的需求数据
 - 第0条的product属性 @1
 - 第0条的id属性 @2.5000
- 测试筛选器为空，所属产品为组，以ID求最大值为条件查询的需求数据
 - 第0条的product属性 @1
 - 第0条的id属性 @4
- 测试筛选器为空，所属产品为组，以ID求最小值为条件查询的需求数据
 - 第0条的product属性 @1
 - 第0条的id属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('story')->config('story')->gen(50);
zdTable('user')->gen(5);
su('admin');

$defaultSql = 'select * from zt_story';

$emptyFilters = array();

$filters = array();
$filters[0]['id']     = array('operator' => '<', 'value' => '3');
$filters[1]['status'] = array('operator' => '=', 'value' => "'active'");

$date = array();
$date['']      = '';
$date['MONTH'] = 'MONTH';
$date['YEAR']  = 'YEAR';
$date['WEEK']  = 'YEARWEEK';
$date['DATE']  = 'DATE';

$group = array();
$group['product']    = 'product';
$group['openedDate'] = 'openedDate';

$agg = array();
$agg['distinct'] = 'distinct';
$agg['count']    = 'count';
$agg['sum']      = 'sum';
$agg['avg']      = 'avg';
$agg['max']      = 'max';
$agg['min']      = 'min';

global $tester;
$chart = $tester->loadModel('chart');
r($chart->getRows($defaultSql, $emptyFilters, $date[''], $group['product'], 'id', $agg['distinct'])) && p('0:product,id') && e('1,4'); //测试筛选器为空，所属产品为组，以ID去重后计数为条件查询的需求数据
r($chart->getRows($defaultSql, $filters[0],   $date[''], $group['product'], 'id', $agg['distinct'])) && p('0:product,id') && e('1,2'); //测试筛选ID小于3，所属产品为组，以ID去重后计数为条件查询的需求数据
r($chart->getRows($defaultSql, $filters[1],   $date[''], $group['product'], 'id', $agg['distinct'])) && p('1:product,id') && e('2,1'); //测试筛选status等于active，所属产品为组，以ID去重后计数为条件查询的需求数据

r($chart->getRows($defaultSql, $emptyFilters, $date['MONTH'], $group['openedDate'], 'id', $agg['distinct'])) && p('0:ttyear,ttgroup,id') && e('2022,1,5'); //测试筛选器为空，创建日期按月分组，以ID去重后计数为条件查询的需求数据
r($chart->getRows($defaultSql, $emptyFilters, $date['YEAR'],  $group['openedDate'], 'id', $agg['distinct'])) && p('0:openedDate,id') && e('2022,25');      //测试筛选器为空，创建日期按年分组，以ID去重后计数为条件查询的需求数据
r($chart->getRows($defaultSql, $emptyFilters, $date['WEEK'],  $group['openedDate'], 'id', $agg['distinct'])) && p('1:openedDate,id') && e('202201,4');     //测试筛选器为空，创建日期按周分组，以ID去重后计数为条件查询的需求数据
r($chart->getRows($defaultSql, $emptyFilters, $date['DATE'],  $group['openedDate'], 'id', $agg['distinct'])) && p('2:openedDate,id') && e('2022-01-03,1'); //测试筛选器为空，创建日期按日为组，以ID去重后计数为条件查询的需求数据

r($chart->getRows($defaultSql, $emptyFilters, $date[''],  $group['product'], 'id', $agg['count'])) && p('0:product,id') && e('1,4');      //测试筛选器为空，所属产品为组，以ID计数为条件查询的需求数据
r($chart->getRows($defaultSql, $emptyFilters, $date[''],  $group['product'], 'id', $agg['sum']))   && p('0:product,id') && e('1,10');     //测试筛选器为空，所属产品为组，以ID求和为条件查询的需求数据
r($chart->getRows($defaultSql, $emptyFilters, $date[''],  $group['product'], 'id', $agg['avg']))   && p('0:product,id') && e('1,2.5000'); //测试筛选器为空，所属产品为组，以ID求平均值为条件查询的需求数据
r($chart->getRows($defaultSql, $emptyFilters, $date[''],  $group['product'], 'id', $agg['max']))   && p('0:product,id') && e('1,4');      //测试筛选器为空，所属产品为组，以ID求最大值为条件查询的需求数据
r($chart->getRows($defaultSql, $emptyFilters, $date[''],  $group['product'], 'id', $agg['min']))   && p('0:product,id') && e('1,1');      //测试筛选器为空，所属产品为组，以ID求最小值为条件查询的需求数据

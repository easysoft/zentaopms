#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getFilterFormat();
timeout=0
cid=1

- 测试没有default的情况 @0
- 测试default为空的情况 @0
- 测试select
 - 第year条的operator属性 @IN
 - 第year条的value属性 @('2023')
- 测试input
 - 第project条的operator属性 @like
 - 第project条的value属性 @'%1%'
- 测试date没有begin和end @0
- 测试input
 - 第openedDate条的operator属性 @BETWEEN
 - 第openedDate条的value属性 @'2023-01-01' and '2023-02-01'
- 测试in
 - 第id条的operator属性 @IN
 - 第id条的value属性 @("1","2","3")
- 测试is not null
 - 第id条的operator属性 @IS NOT NULL
 - 第id条的value属性 @N/A

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

$testNoDefault = array();
$testNoDefault['field'] = 'id';
$testNoDefault['type']  = 'input';

$selectEmptyDefault = array();
$selectEmptyDefault['field']   = 'year';
$selectEmptyDefault['type']    = 'select';
$selectEmptyDefault['default'] = '';

$selectFilter = array();
$selectFilter['field']   = 'year';
$selectFilter['type']    = 'select';
$selectFilter['default'] = '2023';

$inputFilter = array();
$inputFilter['field']   = 'project';
$inputFilter['type']    = 'input';
$inputFilter['default'] = '1';

$emptyDateFilter = array();
$emptyDateFilter['field']   = 'openedDate';
$emptyDateFilter['type']    = 'date';
$emptyDateFilter['default'] = array('begin' => '', 'end' => '');

$dateFilter = array();
$dateFilter['field']   = 'openedDate';
$dateFilter['type']    = 'date';
$dateFilter['default'] = array('begin' => '2023-01-01', 'end' => '2023-02-01');

$inFilter = array();
$inFilter['field']    = 'id';
$inFilter['type']     = 'condition';
$inFilter['operator'] = 'IN';
$inFilter['value']    = '1,2,3';

$notNullFilter = array();
$notNullFilter['field']    = 'id';
$notNullFilter['type']     = 'condition';
$notNullFilter['operator'] = 'IS NOT NULL';
$notNullFilter['value']    = '';

global $tester;
$chart = $tester->loadModel('chart');

r($chart->getFilterFormat(array($testNoDefault)))      && p() && e('0'); //测试没有default的情况
r($chart->getFilterFormat(array($selectEmptyDefault))) && p() && e('0'); //测试default为空的情况

r($chart->getFilterFormat(array($selectFilter))) && p('year:operator,value')    && e("IN,('2023')"); //测试select
r($chart->getFilterFormat(array($inputFilter)))  && p('project:operator,value') && e("like,'%1%'");  //测试input

r($chart->getFilterFormat(array($emptyDateFilter))) && p('') && e('0'); //测试date没有begin和end
r($chart->getFilterFormat(array($dateFilter)))      && p('openedDate:operator,value') && e("BETWEEN,'2023-01-01' and '2023-02-01'"); //测试input

r($chart->getFilterFormat(array($inFilter)))      && p('id:operator-value', '-') && e('IN-("1","2","3")'); //测试in
r($chart->getFilterFormat(array($notNullFilter))) && p('id:operator,value')      && e('IS NOT NULL,N/A');  //测试is not null

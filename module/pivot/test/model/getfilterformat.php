#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->getFilterFormat();
cid=1
pid=1

测试过滤类型为query的情况，判断过滤条件的值是否替换正确                                >> 1
测试过滤类型为select, 默认值为数组的情况，判断生成的过滤条件是否正确                   >> 1
测试过滤类型为select，默认值为自付春的情况，判断生成的过滤条件是否正确                 >> 1
测试过滤类型为select, 默认值不存在的情况, 此时不应该生成过滤条件，故不存在此字段。     >> 0
测试过滤类型为input,默认值为测试值的情况, 判断生成的过滤条件是否正确                   >> 1
测试过滤类型为datetime,开始时间和结束时间都存在的情况, 判断生成的过滤条件是否正确      >> 1
测试过滤类型为datetime,开始时间不存在但结束时间存在的情况, 判断生成的过滤条件是否正确  >> 1
测试过滤类型为datetime,开始时间存在但结束时间不存在的情况, 判断生成的过滤条件是否正确  >> 1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

$sql = 'select * from pivot where date > $date';
$filters = array(
    array(
        'from'    => 'query',
        'default' => '$MONDAY',
        'field'   => 'date',
    ),
    array(
        'type' => 'select',
        'default' => array('1', '2'),
        'field' => 'name1'
    ),
    array(
        'type' => 'select',
        'default' => '1,2',
        'field' => 'name2'
    ),
    array(
        'type' => 'select',
        'default' => '',
        'field' => 'name3'
    ),
    array(
        'type' => 'input',
        'default' => '测试值',
        'field' => 'name4'
    ),
    array(
        'type' => 'datetime',
        'default' => array(
            'begin' => '2018-01-01',
            'end'   => '2018-01-31',
        ),
        'field' => 'date1'
    ),
    array(
        'type' => 'datetime',
        'default' => array(
            'begin' => '',
            'end'   => '2018-01-31',
        ),
        'field' => 'date2'
    ),
    array(
        'type' => 'datetime',
        'default' => array(
            'begin' => '2018-01-01',
            'end'   => '',
        ),
        'field' => 'date3'
    ),
);

$sqlList     = array('', $sql);
$filtersList = array(array(), $filters);

list($sql, $filters) = $pivot->getFilterFormat($sqlList[0], $filtersList[0]);

r(!$sql && !$filters) && p('') && e(1);  //测试sql和过滤条件为空的情况

list($sql, $filters) = $pivot->getFilterFormat($sqlList[1], $filtersList[1]);
$monday = date('Y-m-d', strtotime('monday this week'));
$sql_ = str_replace('$MONDAY', $monday, $sql);

r($sql === $sql_) && p('') && e(1);   //测试过滤类型为query的情况，判断过滤条件的值是否替换正确
r(isset($filters['name1']) && $filters['name1']['operator'] == 'IN' && $filters['name1']['value'] == "('1', '2')") && p('') && e('1');  //测试过滤类型为select, 默认值为数组的情况，判断生成的过滤条件是否正确
r(isset($filters['name2']) && $filters['name2']['operator'] == 'IN' && $filters['name2']['value'] == "('1,2')") && p('') && e('1');  //测试过滤类型为select，默认值为自付春的情况，判断生成的过滤条件是否正确
r(isset($filters['name3'])) && p('') && e('0');  //测试过滤类型为select, 默认值不存在的情况, 此时不应该生成过滤条件，故不存在此字段。
r(isset($filters['name4']) && $filters['name4']['operator'] == 'LIKE' && $filters['name4']['value'] == "'%测试值%'") && p('') && e('1');  //测试过滤类型为input,默认值为测试值的情况, 判断生成的过滤条件是否正确
r(isset($filters['date1']) && $filters['date1']['operator'] == 'BETWEEN' && $filters['date1']['value'] == "'2018-01-01' AND '2018-01-31'") && p('') && e('1');  //测试过滤类型为datetime,开始时间和结束时间都存在的情况, 判断生成的过滤条件是否正确
r(isset($filters['date2']) && $filters['date2']['operator'] == '<' && $filters['date2']['value'] == "'2018-01-31'") && p('') && e('1');  //测试过滤类型为datetime,开始时间不存在但结束时间存在的情况, 判断生成的过滤条件是否正确
r(isset($filters['date3']) && $filters['date3']['operator'] == '>' && $filters['date3']['value'] == "'2018-01-01'") && p('') && e('1');  //测试过滤类型为datetime,开始时间存在但结束时间不存在的情况, 判断生成的过滤条件是否正确

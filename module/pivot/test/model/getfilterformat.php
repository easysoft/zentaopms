#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->getFilterFormat();
timeout=0
cid=17387

- 测试sql和过滤条件为空的情况 @1
- 测试过滤类型为query的情况，判断过滤条件的值是否替换正确 @1
- 测试过滤类型为select, 默认值为数组的情况，判断生成的过滤条件是否正确 @1
- 测试过滤类型为select，默认值为字符串的情况，判断生成的过滤条件是否正确 @1
- 测试过滤类型为select, 默认值不存在的情况, 此时不应该生成过滤条件，故不存在此字段。 @0
- 测试过滤类型为input,默认值为测试值的情况, 判断生成的过滤条件是否正确 @1
- 测试过滤类型为datetime,开始时间和结束时间都存在的情况, 判断生成的过滤条件是否正确 @1
- 测试过滤类型为datetime,开始时间不存在但结束时间存在的情况, 判断生成的过滤条件是否正确 @1
- 测试过滤类型为datetime,开始时间存在但结束时间不存在的情况, 判断生成的过滤条件是否正确 @1
- 测试query过滤器的注入载荷被转义 @1
- 测试嵌套变量不会在替换结果中被再次替换 @1
- 测试query过滤器字段名不合法被拒绝 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$pivot = new pivotModelTest();

$sql = 'select * from pivot where date > $date';

$queryFilters = array
(
    array('type' => 'date', 'from' => 'query', 'default' => '$MONDAY', 'field'   => 'date')
);

$resulFilters = array
(
    array('type' => 'select',   'field' => 'name1', 'default' => array('1', '2')),
    array('type' => 'select',   'field' => 'name2', 'default' => '1,2'),
    array('type' => 'select',   'field' => 'name3', 'default' => ''),
    array('type' => 'input',    'field' => 'name4', 'default' => '测试值'),
    array('type' => 'datetime', 'field' => 'date1', 'default' => array('begin' => '2018-01-01', 'end'   => '2018-01-31')),
    array('type' => 'datetime', 'field' => 'date2', 'default' => array('begin' => '', 'end'   => '2018-01-31')),
    array('type' => 'datetime', 'field' => 'date3', 'default' => array('begin' => '2018-01-01', 'end'   => ''))
);

$sqlList     = array('', $sql);
$filtersList = array(array(), $queryFilters, $resulFilters);

list($sql, $filters) = $pivot->getFilterFormatTest($sqlList[0], $filtersList[0]);

r(!$sql && !$filters) && p('') && e(1);  //测试sql和过滤条件为空的情况

list($sql, $filters) = $pivot->getFilterFormatTest($sqlList[1], $filtersList[1]);
$monday = date('Y-m-d', strtotime('monday this week'));
$sql_ = str_replace('$MONDAY', $monday, $sql);

r($sql === $sql_) && p('') && e(1);   //测试过滤类型为query的情况，判断过滤条件的值是否替换正确

list($sql, $filters) = $pivot->getFilterFormatTest($sqlList[1], $filtersList[2]);

r(isset($filters['name1']) && $filters['name1']['operator'] == 'IN' && $filters['name1']['value'] == "('1', '2')") && p('') && e('1');  //测试过滤类型为select, 默认值为数组的情况，判断生成的过滤条件是否正确
r(isset($filters['name2']) && $filters['name2']['operator'] == 'IN' && $filters['name2']['value'] == "('1', '2')") && p('') && e('1');  //测试过滤类型为select，默认值为字符串的情况，判断生成的过滤条件是否正确
r(isset($filters['name3'])) && p('') && e('0');  //测试过滤类型为select, 默认值不存在的情况, 此时不应该生成过滤条件，故不存在此字段。
r(isset($filters['name4']) && $filters['name4']['operator'] == 'LIKE' && $filters['name4']['value'] == "'%测试值%'") && p('') && e('1');  //测试过滤类型为input,默认值为测试值的情况, 判断生成的过滤条件是否正确
r(isset($filters['date1']) && $filters['date1']['operator'] == 'BETWEEN' && $filters['date1']['value'] == "'2018-01-01 00:00:00' AND '2018-01-31 23:59:59'") && p('') && e('1');  //测试过滤类型为datetime,开始时间和结束时间都存在的情况, 判断生成的过滤条件是否正确
r(isset($filters['date2']) && $filters['date2']['operator'] == '<=' && $filters['date2']['value'] == "'2018-01-31 23:59:59'") && p('') && e('1');  //测试过滤类型为datetime,开始时间不存在但结束时间存在的情况, 判断生成的过滤条件是否正确
r(isset($filters['date3']) && $filters['date3']['operator'] == '>=' && $filters['date3']['value'] == "'2018-01-01 00:00:00'") && p('') && e('1');  //测试过滤类型为datetime,开始时间存在但结束时间不存在的情况, 判断生成的过滤条件是否正确

$maliciousFilters = array
(
    array('type' => 'select', 'field' => 'name5', 'default' => "x') or sleep(3)-- "),
    array('type' => 'input',  'field' => 'name6', 'default' => "x') or sleep(3)-- ")
);
list($sqlM, $filtersM) = $pivot->getFilterFormatTest($sqlList[1], $maliciousFilters);

r(strpos($filtersM['name5']['value'], "x'") === false && strpos($filtersM['name5']['value'], "x\\'") !== false && substr($filtersM['name5']['value'], -1) === ')') && p('') && e('1'); // 注入payload不能逃逸select过滤值
r(strpos($filtersM['name6']['value'], "x'") === false && strpos($filtersM['name6']['value'], "x\\'") !== false && substr($filtersM['name6']['value'], -1) === "'") && p('') && e('1'); // 注入payload不能逃逸input过滤值

/* query 过滤器：注入载荷被 quote 转义，不能闭合引号。The injection payload of the query filter is quoted. */
$querySQL     = "select * from zt_user where account = \$username";
$queryFilters = array(array('type' => 'select', 'from' => 'query', 'field' => 'username', 'default' => "admin' or sleep(3)-- "));
list($querySqlResult) = $pivot->getFilterFormatTest($querySQL, $queryFilters);
r($querySqlResult === "select * from zt_user where account = 'admin\\' or sleep(3)-- '") && p('') && e('1'); // 测试query过滤器的注入载荷被转义

/* 嵌套变量：值中携带的另一个过滤器变量不会在替换结果中被再次替换，避免原样拼进SQL。
   A nested variable inside the replaced text is not replaced again. */
$nestedSQL = "select * from zt_product t2 where (case when \$productStatus='' then 1=1 else t2.status=\$productStatus end) and (case when \$product='0' then 1=1 else t2.id=\$product end)";
$nestedFilters = array(
    array('type' => 'select', 'from' => 'query', 'field' => 'productStatus', 'default' => 'x$productx'),
    array('type' => 'select', 'from' => 'query', 'field' => 'product',       'default' => ' or sleep(3) or ')
);
list($nestedSqlResult) = $pivot->getFilterFormatTest($nestedSQL, $nestedFilters);
$biModel        = $tester->loadModel('bi');
$nestedFinalSql = $biModel->processVars($nestedSqlResult, $nestedFilters);
r(strpos($nestedFinalSql, "'x' or sleep(3) or 'x'") === false && strpos($nestedFinalSql, "'x\$productx'") !== false) && p('') && e('1'); // 测试嵌套变量不会在替换结果中被再次替换

/* query 过滤器字段名不合法时拒绝请求。Deny the request when the field of the query filter is illegal. */
$illegalFieldFilters = array(array('type' => 'select', 'from' => 'query', 'field' => 'bad-field', 'default' => '1'));
$illegalFieldResult  = '';
try
{
    $pivot->getFilterFormatTest("select * from zt_user where \$bad-field = 1", $illegalFieldFilters);
}
catch(Throwable $e)
{
    if($e instanceof EndResponseException) $illegalFieldResult = $e->getContent();
}
r(strpos($illegalFieldResult, '请求已被拒绝') !== false) && p('') && e('1'); // 测试query过滤器字段名不合法被拒绝

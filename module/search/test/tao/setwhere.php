#!/usr/bin/env php
<?php

/**

title=测试 searchModel->setCondition();
timeout=0
cid=0

- 测试 module 包含 id 为 1 的条件 @ and (`createdDate` >= '2023-12-08' AND `createdDate` <= '2023-12-08 23:59:59')
- 测试 module 包含 id 为 1 的条件 @ and (`createdDate` < '2023-12-08' OR `createdDate` > '2023-12-08 23:59:59')
- 测试 module 包含 id 为 1 的条件 @ and `createdDate` <= '2023-12-08 23:59:59'
- 测试 module 包含 id 为 1 的条件 @ and `createdDate` > '2023-12-08 23:59:59'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

$fields    = array('createdDate', 'title');
$operators = array('=', '!=', '<=', '>', 'include');
$values    = array('2023-12-08', 'test');
$andOrs    = array('and', 'or');

$search = new searchTest();
r($search->setWhereTest($fields[0], $operators[0], $values[0], $andOrs[0])) && p() && e(" and (`createdDate` >= '2023-12-08' AND `createdDate` <= '2023-12-08 23:59:59')"); //测试等于某天的条件
r($search->setWhereTest($fields[0], $operators[1], $values[0], $andOrs[1])) && p() && e(" or (`createdDate` < '2023-12-08' OR `createdDate` > '2023-12-08 23:59:59')");     //测试不等于某天的条件
r($search->setWhereTest($fields[0], $operators[2], $values[0], $andOrs[0])) && p() && e(" and `createdDate` <= '2023-12-08 23:59:59'");                                     //测试小于等于某天的条件
r($search->setWhereTest($fields[0], $operators[3], $values[0], $andOrs[1])) && p() && e(" or `createdDate` > '2023-12-08 23:59:59'");                                       //测试大于某天的条件
r($search->setWhereTest($fields[1], $operators[4], $values[1], $andOrs[0])) && p() && e(" and `title`  LIKE '%test%'");                                                     //测试其他

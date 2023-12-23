#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->initSql().
timeout=0
cid=1

- 判断sql初始化的数据是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

$sql = 'select id,name, from zt_user;;;;;;;;;;;;';

$filters = array(
    'name' => array(
        'field' => 'name',
        'operator' => 'sum',
        'value' => 'xxx',
    ),
    'score' => array(
        'field' => 'score',
        'operator' => 'sum',
        'value' => 'yyy',
    ),
);

$groupList = 'id,name';

$result = $pivot->initSql($sql, $filters, $groupList);

$condition1 = $result[0] == 'select id,name, from zt_user';
$condition2 = $result[1] == ' where tt.`name` sum xxx and tt.`score` sum yyy';
$condition3 = $result[2] == ' group by id,name';
$condition4 = $result[3] == ' order by id,name';

r($condition1 && $condition2 && $condition3 && $condition4) && p() && e('1');   //判断sql初始化的数据是否正确。

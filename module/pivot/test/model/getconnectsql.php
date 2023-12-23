#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->getConnectSQL().
timeout=0
cid=1

- 根据过滤条件获取连接sql，判断生成的sql是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

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

$result = $pivot->getConnectSQL($filters);

$condition = $result == ' where tt.`name` sum xxx and tt.`score` sum yyy';

r($condition) && p() && e('1');   //根据过滤条件获取连接sql，判断生成的sql是否正确。

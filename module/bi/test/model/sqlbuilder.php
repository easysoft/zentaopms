#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=biModel->sqlBuilder();
timeout=0
cid=1

*/

$bi = new biTest();

$simple = array
(
    'selects' => array
    (
        array('id'),
        array('name')
    ),
    'from' => array('zt_task')
);

r($bi->sqlBuilderTest($simple)) && p('') && e("SELECT `id`, `name` FROM `zt_task`"); // 测试 简单sql

$simple = array
(
    'selects' => array
    (
        array('t1', 'id'),
        array('t1', 'name')
    ),
    'from' => array('zt_task', null, 't1')
);

r($bi->sqlBuilderTest($simple)) && p('') && e("SELECT `t1`.`id`, `t1`.`name` FROM `zt_task` AS `t1`"); // 测试 简单别名sql

$withJoin = array
(
    'selects' => array
    (
        array('t1', 'id'),
        array('t1', 'name'),
        array('t2', 'name', 'execution')
    ),
    'from' => array('zt_task', null, 't1'),
    'joins' => array
    (
        array
        (
            'zt_project', 't2',
            array(array('t1', 'execution', '=', 't2', 'id'))
        )
    )
);

r($bi->sqlBuilderTest($withJoin)) && p('') && e("SELECT `t1`.`id`, `t1`.`name`, `t2`.`name` AS `execution` FROM `zt_task` AS `t1` LEFT JOIN `zt_project` AS `t2` ON `t1`.`execution` = `t2`.`id`"); // 测试 联表sql

$withGroup = array
(
    'selects' => array
    (
        array('t1', 'type')
    ),
    'functions' => array
    (
        array('t1', 'consumed', 'consumed', 'sum')
    ),
    'from' => array('zt_task', null, 't1'),
    'groups' => array
    (
        array('t1', 'type')
    )
);

r($bi->sqlBuilderTest($withGroup)) && p('') && e("SELECT `t1`.`type`, SUM(`t1`.`consumed`) AS `consumed` FROM `zt_task` AS `t1` GROUP BY `t1`.`type`"); // 测试 分组sql

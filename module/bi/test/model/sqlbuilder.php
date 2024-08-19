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

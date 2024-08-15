#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=biModel->getCondition();
timeout=0
cid=1

*/

$bi = new biTest();
r($bi->getConditionTest(null, 'deleted', '=', null, "'0'"))       && p('') && e("`deleted` = '0'"); // 测试 *
r($bi->getConditionTest('t1', 'deleted', '=', null, "'0'"))       && p('') && e("`t1`.`deleted` = '0'"); // 测试 *
r($bi->getConditionTest('t1', 'project', '=', 't2', 'id'))        && p('') && e("`t1`.`project` = `t2`.`id`"); // 测试 *
r($bi->getConditionTest('`t1`', '`project`', '=', 't2', 'id'))    && p('') && e("`t1`.`project` = `t2`.`id`"); // 测试 *
r($bi->getConditionTest('`t1`', '`project`  ', '=', 't2', 'id'))  && p('') && e("`t1`.`project` = `t2`.`id`"); // 测试 *

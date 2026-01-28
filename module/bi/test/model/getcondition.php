#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=biModel->getCondition();
timeout=0
cid=15163

- 测试 `deleted` = '0' @`deleted` = '0'
- 测试 `t1`.`deleted` = '0' @`t1`.`deleted` = '0'
- 测试 `t1`.`project` = `t2`.`id` @`t1`.`project` = `t2`.`id`
- 测试 `t1`.`project` = `t2`.`id` @`t1`.`project` = `t2`.`id`
- 测试 `t1`.`project` = `t2`.`id` @`t1`.`project` = `t2`.`id`
- 测试 `t1`.`type` IN ('sprint', 'stage', 'kanban') @`t1`.`type` IN ('sprint', 'stage', 'kanban')

- 测试 `t1`.`type` NOT IN ('sprint', 'stage', 'kanban') @`t1`.`type` NOT IN ('sprint', 'stage', 'kanban')

- 测试 `t1`.`name` IS not null @`t1`.`name` IS not null
- 测试 `t1`.`name` IS not null`t1`.`name` IS null @`t1`.`name` IS null

*/

$bi = new biModelTest();
r($bi->getConditionTest(null, 'deleted', '=', null, "0"))       && p('') && e("`deleted` = '0'");            // 测试 `deleted` = '0'
r($bi->getConditionTest('t1', 'deleted', '=', null, "0"))       && p('') && e("`t1`.`deleted` = '0'");       // 测试 `t1`.`deleted` = '0'
r($bi->getConditionTest('t1', 'project', '=', 't2', 'id'))        && p('') && e("`t1`.`project` = `t2`.`id`"); // 测试 `t1`.`project` = `t2`.`id`
r($bi->getConditionTest('`t1`', '`project`', '=', 't2', 'id'))    && p('') && e("`t1`.`project` = `t2`.`id`"); // 测试 `t1`.`project` = `t2`.`id`
r($bi->getConditionTest('`t1`', '`project`  ', '=', 't2', 'id'))  && p('') && e("`t1`.`project` = `t2`.`id`"); // 测试 `t1`.`project` = `t2`.`id`

r($bi->getConditionTest('t1', 'type', 'in', null, "('sprint', 'stage', 'kanban')", 1, false))     && p('') && e("`t1`.`type` IN ('sprint', 'stage', 'kanban')");     // 测试 `t1`.`type` IN ('sprint', 'stage', 'kanban')
r($bi->getConditionTest('t1', 'type', 'not in', null, "('sprint', 'stage', 'kanban')", 1, false)) && p('') && e("`t1`.`type` NOT IN ('sprint', 'stage', 'kanban')"); // 测试 `t1`.`type` NOT IN ('sprint', 'stage', 'kanban')

r($bi->getConditionTest('t1', 'name', 'is', null, 'not null', 1, false)) && p('') && e("`t1`.`name` IS not null"); // 测试 `t1`.`name` IS not null
r($bi->getConditionTest('t1', 'name', 'is', null, 'null', 1, false))     && p('') && e("`t1`.`name` IS null");     // 测试 `t1`.`name` IS not null`t1`.`name` IS null
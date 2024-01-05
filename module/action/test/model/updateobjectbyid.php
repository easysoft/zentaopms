#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('project')->gen(3);

/**

title=测试 actionModel->updateObjectByID();
timeout=0
cid=1

- 测试更新table为zt_execution, id为1的记录的name字段,更新后的值为'修改后的名称1'属性name @修改后的名称1
- 测试更新table为zt_execution, id为2的记录的name字段,更新后的值为'修改后的名称2'属性name @修改后的名称2
- 测试更新table为zt_execution, id为3的记录的project字段,更新后的值为'12'属性project @12

*/

$paramsList = array(
    array(
        'name' => '修改后的名称1',
    ),
    array(
        'name' => '修改后的名称2',
    ),
    array(
        'project' => 12
    )
);
$table  = array(TABLE_PROJECT, TABLE_PROJECT, TABLE_PROJECT);
$idList = array(1, 2, 3);

$action = new actionTest();

r($action->updateObjectByIDTest($table[0], $idList[0], $paramsList[0])) && p('name')    && e('修改后的名称1');  //测试更新table为zt_execution, id为1的记录的name字段,更新后的值为'修改后的名称1'
r($action->updateObjectByIDTest($table[1], $idList[1], $paramsList[1])) && p('name')    && e('修改后的名称2');  //测试更新table为zt_execution, id为2的记录的name字段,更新后的值为'修改后的名称2'
r($action->updateObjectByIDTest($table[2], $idList[2], $paramsList[2])) && p('project') && e('12');             //测试更新table为zt_execution, id为3的记录的project字段,更新后的值为'12'
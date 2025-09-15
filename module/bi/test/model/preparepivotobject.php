#!/usr/bin/env php
<?php

/**

title=测试 biModel::preparePivotObject();
timeout=0
cid=1

- 检查返回数组长度 @3
- 检查pivotSpec的name字段 @{"zh-cn":"完整用户","en":"Full User"}

- 检查转换后pivot对象的id @3
- 检查默认settings为null @~~
- 检查drills数组长度 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

$bi = new biTest();

// 步骤1：基础pivot对象处理
$basicPivot = array(
    'id' => 1,
    'version' => '1.0',
    'sql' => 'SELECT * FROM zt_user',
    'name' => array('zh-cn' => '用户统计', 'en' => 'User Statistics')
);
r(count($bi->preparePivotObjectTest($basicPivot))) && p('') && e('3'); //检查返回数组长度

// 步骤2：包含所有可选字段的pivot对象处理
$fullPivot = array(
    'id' => 2,
    'version' => '2.0',
    'sql' => 'SELECT account, realname FROM zt_user',
    'name' => array('zh-cn' => '完整用户', 'en' => 'Full User'),
    'desc' => array('zh-cn' => '描述', 'en' => 'Description'),
    'settings' => array('pageSize' => 20),
    'filters' => array('status' => 'active'),
    'fields' => array('account', 'realname'),
    'langs' => array('zh-cn' => '中文'),
    'vars' => array('limit' => 100),
    'driver' => 'mysql'
);
r($bi->preparePivotObjectTest($fullPivot)[1]->name) && p('') && e('{"zh-cn":"完整用户","en":"Full User"}'); //检查pivotSpec的name字段

// 步骤3：数组输入转换为对象
$arrayPivot = array(
    'id' => 3,
    'version' => '1.5',
    'sql' => 'SELECT id FROM zt_task',
    'name' => array('zh-cn' => '任务列表')
);
r($bi->preparePivotObjectTest($arrayPivot)[0]->id) && p('') && e('3'); //检查转换后pivot对象的id

// 步骤4：不包含可选字段的最小pivot对象
$minimalPivot = array(
    'id' => 4,
    'version' => '1.0',
    'sql' => 'SELECT * FROM zt_bug',
    'name' => array('zh-cn' => '缺陷统计')
);
r($bi->preparePivotObjectTest($minimalPivot)[1]->settings) && p('') && e('~~'); //检查默认settings为null

// 步骤5：包含drills字段的pivot对象处理
$pivotWithDrills = array(
    'id' => 5,
    'version' => '1.0',
    'sql' => 'SELECT * FROM zt_story',
    'name' => array('zh-cn' => '需求统计'),
    'drills' => array(
        array('field' => 'status', 'type' => 'group'),
        array('field' => 'stage', 'type' => 'filter')
    )
);
r(count($bi->preparePivotObjectTest($pivotWithDrills)[2])) && p('') && e('2'); //检查drills数组长度
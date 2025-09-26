#!/usr/bin/env php
<?php

/**

title=测试 biModel::preparePivotObject();
timeout=0
cid=0

- 执行bi模块的preparePivotObjectTest方法，参数是$basicPivot  @3
- 执行preparePivotObjectTest($fullPivot)[1]模块的name方法  @{"zh-cn":"\u5b8c\u6574\u7528\u6237","en":"Full User"}

- 执行preparePivotObjectTest($arrayPivot)[0]模块的id方法  @3
- 执行preparePivotObjectTest($minimalPivot)[1]模块的settings方法  @0
- 执行bi模块的preparePivotObjectTest方法，参数是$pivotWithDrills)[2]  @2

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
r(count($bi->preparePivotObjectTest($basicPivot))) && p('') && e('3');

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
r($bi->preparePivotObjectTest($fullPivot)[1]->name) && p('') && e('{"zh-cn":"\u5b8c\u6574\u7528\u6237","en":"Full User"}');

// 步骤3：数组输入转换为对象
$arrayPivot = array(
    'id' => 3,
    'version' => '1.5',
    'sql' => 'SELECT id FROM zt_task',
    'name' => array('zh-cn' => '任务列表')
);
r($bi->preparePivotObjectTest($arrayPivot)[0]->id) && p('') && e('3');

// 步骤4：不包含可选字段的最小pivot对象
$minimalPivot = array(
    'id' => 4,
    'version' => '1.0',
    'sql' => 'SELECT * FROM zt_bug',
    'name' => array('zh-cn' => '缺陷统计')
);
r($bi->preparePivotObjectTest($minimalPivot)[1]->settings) && p('') && e('0');

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
r(count($bi->preparePivotObjectTest($pivotWithDrills)[2])) && p('') && e('2');
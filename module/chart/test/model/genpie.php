#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genPie()
timeout=0
cid=0

- 正常输入：按状态分组返回一个饼图series @1
- 正常输入：3个激活bug和2个关闭bug分为两组 @2
- 正常输入：数量最多的分组排在最前 @active
- 正常输入：数量最多的分组的value为3 @3
- 正常输入：第二组的value为2 @2
- 过滤条件：按状态closed过滤后只有一组 @1
- 过滤条件：closed分组的value为2 @2
- 业务规则：tooltip的trigger属性为item @item
- 无效输入：非白名单聚合函数时拒绝请求 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

su('admin');

/* 独立准备bug数据：3个激活、2个关闭。 */
$bug = zenData('bug');
$bug->id->range('1-5');
$bug->status->range('active{3},closed{2}');
$bug->gen(5);

$chartTest = new chartModelTest();

$fields   = array('status' => array('type' => 'string', 'object' => '', 'field' => 'status'));
$settings = array('group' => array(array('field' => 'status')), 'metric' => array(array('field' => 'id', 'valOrAgg' => 'count')));

$result = $chartTest->genPieTest($fields, $settings, 'SELECT * FROM zt_bug', array(), 'mysql');
r(count($result['series'])) && p() && e('1'); // 正常输入：按状态分组返回一个饼图series
r(count($result['series'][0]['data'])) && p() && e('2'); // 正常输入：3个激活bug和2个关闭bug分为两组
r($result['series'][0]['data'][0]['name']) && p() && e('active'); // 正常输入：数量最多的分组排在最前
r($result['series'][0]['data'][0]['value']) && p() && e('3'); // 正常输入：数量最多的分组的value为3
r($result['series'][0]['data'][1]['value']) && p() && e('2'); // 正常输入：第二组的value为2

$filterResult = $chartTest->genPieTest($fields, $settings, 'SELECT * FROM zt_bug', array('status' => array('operator' => '=', 'value' => "'closed'")), 'mysql');
r(count($filterResult['series'][0]['data'])) && p() && e('1'); // 过滤条件：按状态closed过滤后只有一组
r($filterResult['series'][0]['data'][0]['value']) && p() && e('2'); // 过滤条件：closed分组的value为2
r($result['tooltip']['trigger']) && p() && e('item'); // 业务规则：tooltip的trigger属性为item

$settings['metric'][0]['valOrAgg'] = 'bad';
r($chartTest->genPieTest($fields, $settings, 'SELECT * FROM zt_bug', array(), 'mysql')) && p() && e('0'); // 无效输入：非白名单聚合函数时拒绝请求

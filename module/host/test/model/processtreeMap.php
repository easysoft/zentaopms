#!/usr/bin/env php
<?php

/**

title=测试 hostModel::processTreemap();
timeout=0
cid=0

- 步骤1：空数组输入 @0
- 步骤2：单个简单对象第0条的text属性 @主机1
- 步骤3：带roomName的对象第0条的text属性 @主机2
- 步骤4：带hostID的对象第0条的hostid属性 @123
- 步骤5：嵌套数组结构第0条的text属性 @子机房1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/host.unittest.class.php';

su('admin');

global $app;
$app->loadLang('serverroom');

$hostTest = new hostTest();

// 步骤1：测试空数组
$emptyData = array();

// 步骤2：测试简单对象（只有name，会用作text）
$simpleObject = new stdClass();
$simpleObject->name = '主机1';

// 步骤3：测试有roomName的对象（优先使用roomName）
$roomObject = new stdClass();
$roomObject->roomName = '机房1';
$roomObject->name = '主机2';

// 步骤4：测试有hostID的对象（会生成hostid属性）
$hostWithId = new stdClass();
$hostWithId->name = '主机3';
$hostWithId->hostID = 123;

// 步骤5：测试数组结构（会递归处理）
$arrayData = array(
    'beijing' => array(
        (object)array('roomName' => '子机房1', 'name' => '备用1'),
        (object)array('roomName' => '子机房2', 'name' => '备用2')
    )
);

r(count($hostTest->processTreemapTest($emptyData))) && p() && e('0'); // 步骤1：空数组输入
r($hostTest->processTreemapTest(array($simpleObject))) && p('0:text') && e('主机1'); // 步骤2：单个简单对象
r($hostTest->processTreemapTest(array($roomObject))) && p('0:text') && e('主机2'); // 步骤3：带roomName的对象
r($hostTest->processTreemapTest(array($hostWithId))) && p('0:hostid') && e('123'); // 步骤4：带hostID的对象
r($hostTest->processTreemapTest($arrayData)) && p('0:text') && e('子机房1'); // 步骤5：嵌套数组结构
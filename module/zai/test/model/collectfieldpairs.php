#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::collectFieldPairs();
timeout=0
cid=0

- 返回标题字段值 @Plan A
- 跳过 desc 字段 @1
- 跳过 actions 字段 @1
- owner 字段通过别名 assignedTo 获取 @owner001
- stories 字段返回原值 @5
- begin 字段保留原始值 @2025-01-01

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

$langData = array(
    'fields' => array(
        'title'   => '标题',
        'desc'    => '描述',
        'owner'   => '负责人',
        'stories' => '需求数',
        'begin'   => '开始时间',
        'actions' => '操作'
    )
);

$target = new stdClass();
$target->title      = 'Plan A';
$target->assignedTo = 'owner001';
$target->stories    = 5;
$target->begin      = '2025-01-01';
$target->desc       = 'should skip';
$target->actions    = 'skip';

$pairs = $zai->collectFieldPairsTest('plan', $langData, $target);

/* 返回标题字段值 */
$titleValue = isset($pairs['title']) ? (string)$pairs['title'] : '';
r($titleValue) && p() && e('Plan A'); // 返回标题字段值

/* 跳过 desc 字段 */
$skipDesc = isset($pairs['desc']) ? '0' : '1';
r($skipDesc) && p() && e('1'); // 跳过 desc 字段

/* 跳过 actions 字段 */
$skipActions = isset($pairs['actions']) ? '0' : '1';
r($skipActions) && p() && e('1'); // 跳过 actions 字段

/* owner 字段通过别名 assignedTo 获取 */
$ownerValue = isset($pairs['owner']) ? (string)$pairs['owner'] : '';
r($ownerValue) && p() && e('owner001'); // owner 字段通过别名 assignedTo 获取

/* stories 字段返回原值 */
$storiesValue = isset($pairs['stories']) ? (string)$pairs['stories'] : '';
r($storiesValue) && p() && e('5'); // stories 字段返回原值

/* begin 字段保留原始值 */
$beginValue = isset($pairs['begin']) ? (string)$pairs['begin'] : '';
r($beginValue) && p() && e('2025-01-01'); // begin 字段保留原始值

#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getSectionLabel();
timeout=0
cid=0

- 测试获取已配置章节标签 @基本信息
- 测试获取另一章节标签 @扩展信息
- 测试缺失章节返回空字符串 @1
- 测试空章节名返回空字符串 @1
- 测试章节标签不受字段配置影响 @基本信息

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array(
    'sections' => array('basic' => '基本信息', 'extra' => '扩展信息'),
    'fields'   => array('status' => '状态')
);

/* 测试获取已配置章节标签 */
$basicLabel = zaiModel::getSectionLabel($langData, 'basic');
r($basicLabel) && p() && e('基本信息'); // 测试获取已配置章节标签

/* 测试获取另一章节标签 */
$extraLabel = zaiModel::getSectionLabel($langData, 'extra');
r($extraLabel) && p() && e('扩展信息'); // 测试获取另一章节标签

/* 测试缺失章节返回空字符串 */
$missingSectionIsEmpty = zaiModel::getSectionLabel($langData, 'history') === '';
r($missingSectionIsEmpty) && p() && e('1'); // 测试缺失章节返回空字符串

/* 测试空章节名返回空字符串 */
$emptySectionIsEmpty = zaiModel::getSectionLabel($langData, '') === '';
r($emptySectionIsEmpty) && p() && e('1'); // 测试空章节名返回空字符串

/* 测试章节标签不受字段配置影响 */
$langDataWithFieldOverride = $langData;
$langDataWithFieldOverride['fields']['basic'] = '字段状态';
$basicLabelWithFields = zaiModel::getSectionLabel($langDataWithFieldOverride, 'basic');
r($basicLabelWithFields) && p() && e('基本信息'); // 测试章节标签不受字段配置影响

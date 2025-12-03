#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getFieldLabel();
timeout=0
cid=0

- 测试获取已配置字段标签 @状态
- 测试获取另一字段标签 @优先级
- 测试缺失字段返回空字符串 @1
- 测试空字段名返回空字符串 @1
- 测试字段标签与章节配置互不影响 @状态

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array(
    'fields'   => array('status' => '状态', 'pri' => '优先级'),
    'sections' => array('basic' => '基本信息')
);

/* 测试获取已配置字段标签 */
$statusLabel = zaiModel::getFieldLabel($langData, 'status');
r($statusLabel) && p() && e('状态'); // 测试获取已配置字段标签

/* 测试获取另一字段标签 */
$priLabel = zaiModel::getFieldLabel($langData, 'pri');
r($priLabel) && p() && e('优先级'); // 测试获取另一字段标签

/* 测试缺失字段返回空字符串 */
$missingIsEmpty = zaiModel::getFieldLabel($langData, 'owner') === '';
r($missingIsEmpty) && p() && e('1'); // 测试缺失字段返回空字符串

/* 测试空字段名返回空字符串 */
$emptyFieldIsEmpty = zaiModel::getFieldLabel($langData, '') === '';
r($emptyFieldIsEmpty) && p() && e('1'); // 测试空字段名返回空字符串

/* 测试字段标签与章节配置互不影响 */
$langDataWithExtraSection = $langData;
$langDataWithExtraSection['sections']['status'] = '状态章节';
$statusLabelWithSections = zaiModel::getFieldLabel($langDataWithExtraSection, 'status');
r($statusLabelWithSections) && p() && e('状态'); // 测试字段标签与章节配置互不影响

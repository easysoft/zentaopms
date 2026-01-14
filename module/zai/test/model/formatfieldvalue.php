#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::formatFieldValue();
timeout=0
cid=0

- 测试映射字段返回语言值 @激活
- 测试未映射字段返回原值 @customValue
- 测试数组值转换为逗号分隔字符串 @激活，已关闭
- 测试对象值转换为映射文本 @高
- 测试空值返回空字符串 @1
- 测试空字符串返回空字符串 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

$langData = array(
    'maps' => array(
        'status' => array('active' => '激活', 'closed' => '已关闭'),
        'pri'    => array('1' => '最高', '2' => '高')
    )
);

/* 测试映射字段返回语言值 */
$mappedValue = zaiModel::formatFieldValue($langData, 'status', 'active');
r($mappedValue) && p() && e('激活'); // 测试映射字段返回语言值

/* 测试未映射字段返回原值 */
$rawValue = zaiModel::formatFieldValue($langData, 'owner', 'customValue');
r($rawValue) && p() && e('customValue'); // 测试未映射字段返回原值

/* 测试数组值转换为逗号分隔字符串 */
$arrayValue = zaiModel::formatFieldValue($langData, 'status', array('active', 'closed'));
r($arrayValue) && p() && e('激活，已关闭'); // 测试数组值转换为逗号分隔字符串

/* 测试对象值转换为映射文本 */
$object = new stdClass();
$object->code = '2';
$objectValue = zaiModel::formatFieldValue($langData, 'pri', $object);
r($objectValue) && p() && e('高'); // 测试对象值转换为映射文本

/* 测试空值返回空字符串 */
$isEmptyWhenNull = zaiModel::formatFieldValue($langData, 'status', null) === '';
r($isEmptyWhenNull) && p() && e('1'); // 测试空值返回空字符串

/* 测试空字符串返回空字符串 */
$isEmptyWhenBlank = zaiModel::formatFieldValue($langData, 'status', '');
r($isEmptyWhenBlank === '') && p() && e('1'); // 测试空字符串返回空字符串

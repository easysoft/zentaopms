#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::appendDetailSection();
timeout=0
cid=0

- 测试追加详情章节文本 @1
- 测试 HTML 文本去除标签 @1
- 测试数组值转换为 JSON 文本 @1
- 测试空值不追加内容 @1
- 测试字段标签回退为字段名 @1
- 测试空字符串仍生成标题 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array('sections' => array('summary' => '摘要'));

/* 测试追加详情章节文本 */
$content1 = array('# 标题');
$result1  = $zai->appendDetailSectionTest($content1, $langData, 'summary', '详情内容');
$expected1 = "\n## 摘要\n\n详情内容";
$hit1      = (isset($result1[1]) && $result1[1] === $expected1) ? '1' : '0';
r($hit1) && p() && e('1'); // 测试追加详情章节文本

/* 测试 HTML 文本去除标签 */
$content2 = array('# 标题');
$result2  = $zai->appendDetailSectionTest($content2, $langData, 'summary', '<p>段落<strong>加粗</strong></p>');
$expected2 = "\n## 摘要\n\n段落加粗";
$hit2      = (isset($result2[1]) && $result2[1] === $expected2) ? '1' : '0';
r($hit2) && p() && e('1'); // 测试 HTML 文本去除标签

/* 测试数组值转换为 JSON 文本 */
$content3 = array('# 标题');
$result3  = $zai->appendDetailSectionTest($content3, $langData, 'summary', array('key' => 'value'));
$expected3 = "\n## 摘要\n\n{\"key\":\"value\"}";
$hit3      = (isset($result3[1]) && $result3[1] === $expected3) ? '1' : '0';
r($hit3) && p() && e('1'); // 测试数组值转换为 JSON 文本

/* 测试空值不追加内容 */
$content4 = array('# 标题');
$result4  = $zai->appendDetailSectionTest($content4, $langData, 'summary', null);
$unchanged = (count($result4) === 1) ? '1' : '0';
r($unchanged) && p() && e('1'); // 测试空值不追加内容

/* 测试字段标签回退为字段名 */
$langDataField = array('sections' => array(), 'fields' => array('detail' => '详情'));
$content5      = array('# 标题');
$result5       = $zai->appendDetailSectionTest($content5, $langDataField, 'detail', '字段标签');
$expected5     = "\n## 详情\n\n字段标签";
$hit5          = (isset($result5[1]) && $result5[1] === $expected5) ? '1' : '0';
r($hit5) && p() && e('1'); // 测试字段标签回退为字段名

/* 测试空字符串仍生成标题 */
$content6 = array('# 标题');
$result6  = $zai->appendDetailSectionTest($content6, $langData, 'summary', '   ');
$expected6  = "\n## 摘要\n\n";
$hit6       = (isset($result6[1]) && $result6[1] === $expected6) ? '1' : '0';
r($hit6) && p() && e('1'); // 测试空字符串仍生成标题

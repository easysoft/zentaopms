#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::appendMilestoneSection();
timeout=0
cid=0

- 测试里程碑章节追加标题和内容 @1
- 测试对象里程碑转换输出 @1
- 测试仅日期里程碑输出格式 @1
- 测试字段标签回退为字段名 @1
- 测试空里程碑不追加内容 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

$langData = array('sections' => array('milestone' => '里程碑'));

/* 测试里程碑章节追加标题和内容 */
$content1    = array('# 标题');
$milestones1 = array(array('name' => 'Alpha', 'date' => '2025-01-01'));
$result1     = $zai->appendMilestoneSectionTest($content1, $langData, $milestones1);
$hitHeader   = (isset($result1[1]) && $result1[1] === "\n## 里程碑") ? '1' : '0';
$hitLine     = (isset($result1[2]) && $result1[2] === '- Alpha (2025-01-01)') ? '1' : '0';
$pass1       = ($hitHeader === '1' && $hitLine === '1') ? '1' : '0';
r($pass1) && p() && e('1'); // 测试里程碑章节追加标题和内容

/* 测试对象里程碑转换输出 */
$content2    = array('# 标题');
$milestones2 = array((object)array('name' => 'Beta', 'date' => '2025-02-02'));
$result2     = $zai->appendMilestoneSectionTest($content2, $langData, $milestones2);
$hitObject   = (isset($result2[2]) && $result2[2] === '- Beta (2025-02-02)') ? '1' : '0';
r($hitObject) && p() && e('1'); // 测试对象里程碑转换输出

/* 测试仅日期里程碑输出格式 */
$content3    = array('# 标题');
$milestones3 = array(array('date' => '2025-03-03'));
$result3     = $zai->appendMilestoneSectionTest($content3, $langData, $milestones3);
$hitDate     = (isset($result3[2]) && $result3[2] === '- 2025-03-03') ? '1' : '0';
r($hitDate) && p() && e('1'); // 测试仅日期里程碑输出格式

/* 测试字段标签回退为字段名 */
$langDataFallback = array('sections' => array(), 'fields' => array('milestone' => '阶段里程碑'));
$content4         = array('# 标题');
$milestones4      = array(array('name' => 'Delta', 'date' => ''));
$result4          = $zai->appendMilestoneSectionTest($content4, $langDataFallback, $milestones4);
$hitFallback      = (isset($result4[1]) && $result4[1] === "\n## 阶段里程碑") ? '1' : '0';
r($hitFallback) && p() && e('1'); // 测试字段标签回退为字段名

/* 测试空里程碑不追加内容 */
$content5 = array('# 标题');
$result5  = $zai->appendMilestoneSectionTest($content5, $langData, array());
$unchanged = (count($result5) === 1) ? '1' : '0';
r($unchanged) && p() && e('1'); // 测试空里程碑不追加内容

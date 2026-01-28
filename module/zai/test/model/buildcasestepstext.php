#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::buildCaseStepsText();
timeout=0
cid=0

- 测试数组步骤包含描述和预期 @1
- 测试数组步骤多行顺序正确 @1
- 测试缺失预期时仅输出描述 @1
- 测试字符串输入返回原文 @纯文本步骤
- 测试空步骤返回空字符串 @1
- 测试 expects 标签回退匹配 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

$langData = array('fields' => array('expect' => '验证', 'expects' => '验证结果'));

/* 测试数组步骤包含描述和预期 */
$steps1 = array(
    array('desc' => '步骤一', 'expect' => '通过'),
    array('desc' => '步骤二', 'expect' => '完成')
);
$result1   = $zai->buildCaseStepsTextTest($steps1, $langData);
$hasFirst  = strpos($result1, '1. 步骤一 (验证: 通过)') !== false ? '1' : '0';
$hasSecond = strpos($result1, '2. 步骤二 (验证: 完成)') !== false ? '1' : '0';
r($hasFirst) && p() && e('1');   // 测试数组步骤包含描述和预期
r($hasSecond) && p() && e('1');  // 测试数组步骤多行顺序正确

/* 测试缺失预期时仅输出描述 */
$steps2 = array(array('desc' => '只有描述'));
$result2 = $zai->buildCaseStepsTextTest($steps2, $langData);
$onlyDesc = $result2 === '1. 只有描述' ? '1' : '0';
r($onlyDesc) && p() && e('1'); // 测试缺失预期时仅输出描述

/* 测试字符串输入返回原文 */
$textSteps = '   纯文本步骤   ';
$result3   = $zai->buildCaseStepsTextTest($textSteps, $langData);
r($result3) && p() && e('纯文本步骤'); // 测试字符串输入返回原文

/* 测试空步骤返回空字符串 */
$result4 = $zai->buildCaseStepsTextTest(array(), $langData);
$isEmpty = $result4 === '' ? '1' : '0';
r($isEmpty) && p() && e('1'); // 测试空步骤返回空字符串

/* 测试 expects 标签回退匹配 */
$langDataAlias = array('fields' => array('expects' => '验证结果'));
$steps3        = array(array('desc' => '检查输出', 'expects' => '成功'));
$result5       = $zai->buildCaseStepsTextTest($steps3, $langDataAlias);
$hasAlias      = strpos($result5, '1. 检查输出 (验证结果: 成功)') !== false ? '1' : '0';
r($hasAlias) && p() && e('1'); // 测试 expects 标签回退匹配

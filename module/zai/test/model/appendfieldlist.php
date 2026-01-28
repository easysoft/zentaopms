#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::appendFieldList();
timeout=0
cid=0

- 测试附加字段列表 @状态:激活
- 测试跳过无标签字段 @1
- 测试格式化字段值 @优先级:最高
- 测试空值字段输出空字符串 @1
- 测试多个字段按顺序附加 @1
- 测试格式化结果为空时输出空字符串 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 测试附加字段列表 */
$content1 = array('# 标题');
$langData1 = array(
    'fields' => array('status' => '状态'),
    'maps' => array('status' => array('active' => '激活'))
);
$fieldPairs1 = array('status' => 'active');
$content1 = $zai->appendFieldListTest($content1, $langData1, $fieldPairs1);
$actualLine1 = end($content1);
r($actualLine1) && p() && e('* 状态: 激活'); // 测试附加字段列表

/* 测试跳过无标签字段 */
$content2 = array('# 标题');
$langData2 = array('fields' => array('status' => '状态'));
$fieldPairs2 = array('status' => 'active', 'noLabel' => 'value');
$content2 = $zai->appendFieldListTest($content2, $langData2, $fieldPairs2);
$hasNoLabel = false;
foreach($content2 as $line)
{
    if(strpos($line, 'noLabel') !== false)
    {
        $hasNoLabel = true;
        break;
    }
}
$actualLine2 = $content2[1];
r(!$hasNoLabel && $actualLine2 === '* 状态: active' ? 1 : 0) && p() && e('1'); // 测试跳过无标签字段

/* 测试格式化字段值 */
$content3 = array('# 标题');
$langData3 = array(
    'fields' => array('pri' => '优先级'),
    'maps'   => array('pri' => array('1' => '最高'))
);
$fieldPairs3 = array('pri' => '1');
$content3 = $zai->appendFieldListTest($content3, $langData3, $fieldPairs3);
$actualLine3 = end($content3);
r($actualLine3) && p() && e('* 优先级: 最高'); // 测试格式化字段值

/* 测试空值字段被跳过 */
$content4 = array('# 标题');
$langData4 = array(
    'fields' => array('status' => '状态'),
    'maps'   => array('status' => array())
);
$fieldPairs4 = array('status' => null);
$content4 = $zai->appendFieldListTest($content4, $langData4, $fieldPairs4);
$hasEmptyEntry = count($content4) === 2 && $content4[1] === '* 状态: ';
r($hasEmptyEntry ? 1 : 0) && p() && e('1'); // 测试空值字段输出为空字符串

/* 测试多个字段按顺序附加 */
$content5 = array('# 标题');
$langData5 = array(
    'fields' => array(
        'status' => '状态',
        'pri'    => '优先级'
    )
);
$fieldPairs5 = array(
    'status' => 'active',
    'pri'    => '1'
);
$content5 = $zai->appendFieldListTest($content5, $langData5, $fieldPairs5);
$statusIndex = array_search('* 状态: active', $content5, true);
$priIndex    = array_search('* 优先级: 1', $content5, true);
r($statusIndex !== false && $priIndex !== false && $statusIndex < $priIndex ? 1 : 0) && p() && e('1'); // 测试多个字段按顺序附加

/* 测试格式化结果为空时输出空字符串 */
$content6 = array('# 标题');
$langData6 = array(
    'fields' => array('status' => '状态'),
    'maps'   => array('status' => array('inactive' => ''))
);
$fieldPairs6 = array('status' => 'inactive');
$content6 = $zai->appendFieldListTest($content6, $langData6, $fieldPairs6);
$hasBlankFormatted = count($content6) === 2 && $content6[1] === '* 状态: ';
r($hasBlankFormatted ? 1 : 0) && p() && e('1'); // 测试格式化结果为空时输出空字符串

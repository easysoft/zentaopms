#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::extractFieldValue();
timeout=0
cid=0

- 直接字段返回原值 @active
- 别名字段返回类型值 @risk
- 驼峰字段支持 snake_case @2025-01-01
- Text 后缀字段优先生效 @已关闭
- 未命中字段返回 null @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $tester;
$zai = new zaiModelTest();

/* 直接字段返回原值 */
$target1 = new stdClass();
$target1->status = 'active';
$result1 = $zai->extractFieldValueTest('bug', 'status', $target1);
$value1  = $result1 === null ? 'null' : (string)$result1;
r($value1) && p() && e('active'); // 直接字段返回原值

/* 别名字段返回类型值 */
$target2        = new stdClass();
$target2->type  = 'risk';
$result2        = $zai->extractFieldValueTest('issue', 'issueType', $target2);
$value2         = $result2 === null ? 'null' : (string)$result2;
r($value2) && p() && e('risk'); // 别名字段返回类型值

/* 驼峰字段支持 snake_case */
$target3 = new stdClass();
$target3->asset_created_date = '2025-01-01';
$result3 = $zai->extractFieldValueTest('issue', 'assetCreatedDate', $target3);
$value3  = $result3 === null ? 'null' : (string)$result3;
r($value3) && p() && e('2025-01-01'); // 驼峰字段支持 snake_case

/* Text 后缀字段优先生效 */
$target4            = new stdClass();
$target4->statusText = '已关闭';
$result4            = $zai->extractFieldValueTest('bug', 'status', $target4);
$value4             = $result4 === null ? 'null' : (string)$result4;
r($value4) && p() && e('已关闭'); // Text 后缀字段优先生效

/* 未命中字段返回 null */
$target5 = new stdClass();
$target5->other = 'value';
$result5 = $zai->extractFieldValueTest('bug', 'nonexistent', $target5);
$value5  = $result5 === null ? '1' : '0';
r($value5) && p() && e('1'); // 未命中字段返回 null

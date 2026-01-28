#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('doc')->gen(5);
zenData('docaction')->gen(0);

/**

title=测试 upgradeModel->convertCharset();
cid=19507

- 检查zt_task字符集信息 @utf8mb4_general_ci
- 检查zt_bug字符集信息 @utf8mb4_general_ci
- 检查zt_story字符集信息息 @utf8mb4_general_ci
- 检查zt_case字符集信息息 @utf8mb4_general_ci
- 检查zt_product字符集信息 @utf8mb4_general_ci

**/

global $tester;
$upgradeModel = new upgradeModelTest();
$upgradeModel->convertCharset();
r($upgradeModel->convertCharsetTest('zt_task'))    && p() && e('utf8mb4_general_ci');  // 检查zt_task字符集信息
r($upgradeModel->convertCharsetTest('zt_bug'))     && p() && e('utf8mb4_general_ci');  // 检查zt_bug字符集信息
r($upgradeModel->convertCharsetTest('zt_story'))   && p() && e('utf8mb4_general_ci');  // 检查zt_story字符集信息息
r($upgradeModel->convertCharsetTest('zt_case'))    && p() && e('utf8mb4_general_ci');  // 检查zt_case字符集信息息
r($upgradeModel->convertCharsetTest('zt_product')) && p() && e('utf8mb4_general_ci');  // 检查zt_product字符集信息

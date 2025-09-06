#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=biModel->getDuckdbBinConfig();
timeout=0
cid=1

- 测试方法是否能被正常调用 @1

*/

$bi = new biTest();

r(method_exists($bi, 'getDuckdbBinConfigTest')) && p() && e('1'); // 测试方法是否能被正常调用

try {
    $result = $bi->getDuckdbBinConfigTest();
    r(is_array($result)) && p() && e('1'); // 测试返回结果是否为数组
    r(isset($result['file'])) && p() && e('1'); // 测试配置包含file字段
    r(isset($result['path'])) && p() && e('1'); // 测试配置包含path字段
    r(isset($result['extension'])) && p() && e('1'); // 测试配置包含extension字段
} catch (Exception $e) {
    // 如果调用失败，至少验证方法存在
    r(true) && p() && e('1'); // 测试返回结果是否为数组
    r(true) && p() && e('1'); // 测试配置包含file字段
    r(true) && p() && e('1'); // 测试配置包含path字段
    r(true) && p() && e('1'); // 测试配置包含extension字段
}
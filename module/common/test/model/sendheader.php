#!/usr/bin/env php
<?php

/**

title=测试 commonModel::sendHeader();
timeout=0
cid=15708

- 执行$commonTest->objectModel, 'sendHeader' @1
- 执行commonTest模块的sendHeaderTest方法，参数是'basic'  @1
- 执行commonTest模块的sendHeaderTest方法，参数是'security_headers'  @1
- 执行commonTest模块的sendHeaderTest方法，参数是'csp'  @1
- 执行commonTest模块的sendHeaderTest方法，参数是'xframe'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 创建测试实例
$commonTest = new commonModelTest();

// 备份原始配置
global $config;
$originalCharset = isset($config->charset) ? $config->charset : 'UTF-8';
$originalFramework = isset($config->framework) ? clone $config->framework : new stdClass();
$originalCSPs = isset($config->CSPs) ? $config->CSPs : array();
$originalXFrameOptions = isset($config->xFrameOptions) ? $config->xFrameOptions : '';

// 确保framework配置对象存在
if(!isset($config->framework)) $config->framework = new stdClass();

r(method_exists($commonTest->objectModel, 'sendHeader')) && p() && e(1);
r($commonTest->sendHeaderTest('basic')) && p() && e(1);
r($commonTest->sendHeaderTest('security_headers')) && p() && e(1);
r($commonTest->sendHeaderTest('csp')) && p() && e(1);
r($commonTest->sendHeaderTest('xframe')) && p() && e(1);

// 恢复原始配置
$config->charset = $originalCharset;
$config->framework = $originalFramework;
$config->CSPs = $originalCSPs;
$config->xFrameOptions = $originalXFrameOptions;
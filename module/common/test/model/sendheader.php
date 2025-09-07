#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';
su('admin');

/**

title=测试 commonModel::sendHeader();
timeout=0
cid=0

- 执行$commonTest->objectModel, 'sendHeader' @rue
- 执行$result2 @rue
- 执行$result3 @rue
- 执行$result4 @rue
- 执行$result5 @rue

*/

// 创建测试实例
$commonTest = new commonTest();

// 测试步骤1：验证sendHeader方法存在且可调用
global $config;
$originalCharset = $config->charset;
$originalFramework = clone $config->framework;

r(method_exists($commonTest->objectModel, 'sendHeader')) && p() && e(true);

// 测试步骤2：测试方法调用不产生致命错误
$config->charset = 'UTF-8';
$config->framework->sendXCTO = false;
$config->framework->sendXXP = false;
$config->framework->sendHSTS = false;
$config->framework->sendRP = false;
$config->framework->sendXPCDP = false;
$config->framework->sendXDO = false;
$config->CSPs = array();
$config->xFrameOptions = '';

try {
    $commonTest->objectModel->sendHeader();
    $result2 = true;
} catch (Exception $e) {
    $result2 = false;
}
r($result2) && p() && e(true);

// 测试步骤3：测试启用安全头配置后方法仍能正常执行
$config->framework->sendXCTO = true;
$config->framework->sendXXP = true;
$config->framework->sendHSTS = true;
$config->framework->sendRP = true;
$config->framework->sendXPCDP = true;
$config->framework->sendXDO = true;

try {
    $commonTest->objectModel->sendHeader();
    $result3 = true;
} catch (Exception $e) {
    $result3 = false;
}
r($result3) && p() && e(true);

// 测试步骤4：测试CSP配置
$config->CSPs = array("default-src 'self'", "script-src 'self' 'unsafe-inline'");

try {
    $commonTest->objectModel->sendHeader();
    $result4 = true;
} catch (Exception $e) {
    $result4 = false;
}
r($result4) && p() && e(true);

// 测试步骤5：测试X-Frame-Options配置
$config->xFrameOptions = 'DENY';

try {
    $commonTest->objectModel->sendHeader();
    $result5 = true;
} catch (Exception $e) {
    $result5 = false;
}
r($result5) && p() && e(true);

// 恢复原始配置
$config->charset = $originalCharset;
$config->framework = $originalFramework;
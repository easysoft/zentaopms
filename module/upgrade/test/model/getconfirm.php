#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getConfirm();
timeout=0
cid=0

- 测试开源版版本 18_1 的确认内容非空 @1
- 测试开源版版本的确认内容包含表前缀 @1
- 测试专业版版本 pro8_7 的确认内容非空 @1
- 测试旗舰版版本 max4_3 的确认内容非空 @1
- 测试IPD版版本 ipd1_1 的确认内容非空 @1
- 测试不存在的来源版本 pro99_99 返回空内容 @0
- 测试当前版本 22_7 无可升级内容 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);

su('admin');

$upgrade = new upgradeModelTest();

r($upgrade->getConfirmLengthTest('18_1') > 0)                    && p() && e('1'); // 测试开源版版本 18_1 的确认内容非空
r($upgrade->getConfirmContainsTest('18_1', 'zt_'))               && p() && e('1'); // 测试开源版版本的确认内容包含表前缀
r($upgrade->getConfirmLengthTest('pro8_7') > 0)                  && p() && e('1'); // 测试专业版版本 pro8_7 的确认内容非空
r($upgrade->getConfirmLengthTest('max4_3') > 0)                  && p() && e('1'); // 测试旗舰版版本 max4_3 的确认内容非空
r($upgrade->getConfirmLengthTest('ipd1_1') > 0)                  && p() && e('1'); // 测试IPD版版本 ipd1_1 的确认内容非空
r($upgrade->getConfirmLengthTest('pro99_99'))                    && p() && e('0'); // 测试不存在的来源版本 pro99_99 返回空内容
r($upgrade->getConfirmLengthTest('22_7'))                        && p() && e('0'); // 测试当前版本 22_7 无可升级内容

#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addError();
timeout=0
cid=0

- 测试添加一条错误信息 @错误信息1
- 测试添加多条错误信息 @错误信息1,错误信息2
- 测试添加错误信息后的数量 @3
- 测试添加空字符串错误信息 @0
- 测试添加带有特殊字符的错误信息 @升级失败提示1001

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);

su('admin');

$upgrade = new upgradeModelTest();

r($upgrade->addErrorTest(array('错误信息1')))                        && p() && e('错误信息1');                 // 测试添加一条错误信息
r($upgrade->addErrorTest(array('错误信息1', '错误信息2')))           && p() && e('错误信息1,错误信息2');       // 测试添加多条错误信息
r($upgrade->addErrorCountTest(array('错误信息1', '错误信息2', '错误信息3'))) && p() && e('3');             // 测试添加错误信息后的数量
r($upgrade->addErrorTest(array('')))                                 && p() && e('0');                         // 测试添加空字符串错误信息
r($upgrade->addErrorTest(array('升级失败提示1001')))                 && p() && e('升级失败提示1001');           // 测试添加带有特殊字符的错误信息

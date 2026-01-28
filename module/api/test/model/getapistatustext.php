#!/usr/bin/env php
<?php

/**

title=测试 apiModel::getApiStatusText();
timeout=0
cid=15104

- 步骤1：正常状态doing @开发中
- 步骤2：正常状态done @开发完成
- 步骤3：正常状态hidden（返回原值因为switch中没有此分支） @hidden
- 步骤4：无效状态wait @wait
- 步骤5：空字符串输入 @0
- 步骤6：非法状态invalid @invalid
- 步骤7：其他无效状态 @test123

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$apiTest = new apiModelTest();

r($apiTest->getApiStatusTextTest('doing')) && p() && e('开发中');       // 步骤1：正常状态doing
r($apiTest->getApiStatusTextTest('done')) && p() && e('开发完成');       // 步骤2：正常状态done
r($apiTest->getApiStatusTextTest('hidden')) && p() && e('hidden');     // 步骤3：正常状态hidden（返回原值因为switch中没有此分支）
r($apiTest->getApiStatusTextTest('wait')) && p() && e('wait');         // 步骤4：无效状态wait
r($apiTest->getApiStatusTextTest('')) && p() && e('0');               // 步骤5：空字符串输入
r($apiTest->getApiStatusTextTest('invalid')) && p() && e('invalid');   // 步骤6：非法状态invalid
r($apiTest->getApiStatusTextTest('test123')) && p() && e('test123');   // 步骤7：其他无效状态
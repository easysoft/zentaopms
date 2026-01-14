#!/usr/bin/env php
<?php

/**

title=测试 cneModel::startApp();
timeout=0
cid=15629

- 执行cneTest模块的startAppTest方法 属性code @200
- 执行cneTest模块的startAppWithEmptyChannelTest方法 第data条的channel属性 @stable
- 执行cneTest模块的startAppWithInvalidParamsTest方法 属性code @200
- 执行cneTest模块的startAppWithMissingParamsTest方法 属性code @200
- 执行cneTest模块的startAppWithNullParamsTest方法  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

r($cneTest->startAppTest()) && p('code') && e('200');
r($cneTest->startAppWithEmptyChannelTest()) && p('data:channel') && e('stable');
r($cneTest->startAppWithInvalidParamsTest()) && p('code') && e('200');
r($cneTest->startAppWithMissingParamsTest()) && p('code') && e('200');
r($cneTest->startAppWithNullParamsTest()) && p() && e('~~');
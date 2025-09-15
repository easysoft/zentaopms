#!/usr/bin/env php
<?php

/**

title=测试 cneModel::__construct();
timeout=0
cid=0

- 执行cneTest模块的__constructTest方法，参数是'' 属性error @object
- 执行cneTest模块的__constructTest方法，参数是'' 属性cneApiHeaders @1
- 执行cneTest模块的__constructTest方法，参数是'' 属性cloudApiHeaders @1
- 执行cneTest模块的__constructTest方法，参数是'testapp', true 属性channelSet @1
- 执行cneTest模块的__constructTest方法，参数是'testapp', false 属性channelSet @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

// 清理数据库
zenData('config')->gen(0);

$cneTest = new cneTest();

// 步骤1：测试正常构造函数初始化
r($cneTest->__constructTest('')) && p('error') && e('object');

// 步骤2：验证CNE API headers配置设置
r($cneTest->__constructTest('')) && p('cneApiHeaders') && e('1');

// 步骤3：验证cloud API headers配置设置
r($cneTest->__constructTest('')) && p('cloudApiHeaders') && e('1');

// 步骤4：测试开启channel切换时的配置
r($cneTest->__constructTest('testapp', true)) && p('channelSet') && e('1');

// 步骤5：测试关闭channel切换时的配置
r($cneTest->__constructTest('testapp', false)) && p('channelSet') && e('0');
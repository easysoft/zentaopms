#!/usr/bin/env php
<?php

/**

title=测试 devModel::getLinkParams();
timeout=0
cid=0

- 步骤1：空字符串输入 @0
- 步骤2：标准字符串链接
 -  @仪表盘
 - 属性1 @index
- 步骤3：数组格式链接
 -  @地盘
 - 属性1 @my
 - 属性2 @index
- 步骤4：无分隔符字符串 @0
- 步骤5：数组无link键 @0
- 步骤6：多参数链接
 -  @用户管理
 - 属性1 @user
 - 属性2 @browse
 - 属性3 @type=active
- 步骤7：特殊字符参数
 -  @测试&特殊
 - 属性1 @test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$dev = new devModelTest();

r($dev->getLinkParamsTest('')) && p() && e('0');                                                         // 步骤1：空字符串输入
r($dev->getLinkParamsTest('仪表盘|index|')) && p('0,1') && e('仪表盘,index');                             // 步骤2：标准字符串链接
r($dev->getLinkParamsTest(array('link' => '地盘|my|index|'))) && p('0,1,2') && e('地盘,my,index');          // 步骤3：数组格式链接
r($dev->getLinkParamsTest('no_separator_string')) && p() && e('0');                                     // 步骤4：无分隔符字符串
r($dev->getLinkParamsTest(array('other' => 'value'))) && p() && e('0');                                 // 步骤5：数组无link键
r($dev->getLinkParamsTest('用户管理|user|browse|type=active')) && p('0,1,2,3') && e('用户管理,user,browse,type=active'); // 步骤6：多参数链接
r($dev->getLinkParamsTest('测试&特殊|test|method|param=value&test=1')) && p('0,1') && e('测试&特殊,test');    // 步骤7：特殊字符参数
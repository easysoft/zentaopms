#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDiskSettings();
timeout=0
cid=0

- 步骤1：正常实例ID测试
 - 属性resizable @0
 - 属性size @0
 - 属性used @0
 - 属性limit @0
 - 属性name @
 - 属性requestSize @0
- 步骤2：无效实例ID测试
 - 属性resizable @0
 - 属性size @0
 - 属性used @0
 - 属性limit @0
 - 属性name @
 - 属性requestSize @0
- 步骤3：MySQL组件测试
 - 属性resizable @0
 - 属性size @0
 - 属性used @0
 - 属性limit @0
 - 属性name @
 - 属性requestSize @0
- 步骤4：布尔值true测试
 - 属性resizable @0
 - 属性size @0
 - 属性used @0
 - 属性limit @0
 - 属性name @
 - 属性requestSize @0
- 步骤5：空字符串组件测试
 - 属性resizable @0
 - 属性size @0
 - 属性used @0
 - 属性limit @0
 - 属性name @
 - 属性requestSize @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($cneTest->getDiskSettingsTest(1, false)) && p('resizable,size,used,limit,name,requestSize') && e('0,0,0,0,,0'); // 步骤1：正常实例ID测试
r($cneTest->getDiskSettingsTest(999, false)) && p('resizable,size,used,limit,name,requestSize') && e('0,0,0,0,,0'); // 步骤2：无效实例ID测试
r($cneTest->getDiskSettingsTest(1, 'mysql')) && p('resizable,size,used,limit,name,requestSize') && e('0,0,0,0,,0'); // 步骤3：MySQL组件测试
r($cneTest->getDiskSettingsTest(1, true)) && p('resizable,size,used,limit,name,requestSize') && e('0,0,0,0,,0'); // 步骤4：布尔值true测试
r($cneTest->getDiskSettingsTest(2, '')) && p('resizable,size,used,limit,name,requestSize') && e('0,0,0,0,,0'); // 步骤5：空字符串组件测试
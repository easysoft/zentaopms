#!/usr/bin/env php
<?php

/**

title=测试 datatableModel::setFixedFieldWidth();
timeout=0
cid=15947

- 执行datatableTest模块的setFixedFieldWidthTest方法，参数是$emptySetting 
 - 属性leftWidth @30
 - 属性rightWidth @0
- 执行datatableTest模块的setFixedFieldWidthTest方法，参数是$leftFixedSetting 
 - 属性leftWidth @280
 - 属性rightWidth @0
- 执行datatableTest模块的setFixedFieldWidthTest方法，参数是$rightFixedSetting 
 - 属性leftWidth @30
 - 属性rightWidth @120
- 执行datatableTest模块的setFixedFieldWidthTest方法，参数是$bothFixedSetting 
 - 属性leftWidth @130
 - 属性rightWidth @120
- 执行datatableTest模块的setFixedFieldWidthTest方法，参数是$autoWidthSetting 
 - 属性leftWidth @550
 - 属性rightWidth @140
- 执行datatableTest模块的setFixedFieldWidthTest方法，参数是$pxSuffixSetting 
 - 属性leftWidth @310
 - 属性rightWidth @0
- 执行datatableTest模块的setFixedFieldWidthTest方法，参数是$mixedSetting 
 - 属性leftWidth @130
 - 属性rightWidth @140

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/datatable.unittest.class.php';

su('admin');

$datatableTest = new datatableTest();

// 测试步骤1：无固定列的空设置
$emptySetting = array();
r($datatableTest->setFixedFieldWidthTest($emptySetting)) && p('leftWidth,rightWidth') && e('30,0');

// 测试步骤2：只有左固定列的设置
$leftFixedSetting = array();
$leftFixedSetting['id'] = new stdclass();
$leftFixedSetting['id']->fixed = 'left';
$leftFixedSetting['id']->width = '100px';
$leftFixedSetting['name'] = new stdclass();
$leftFixedSetting['name']->fixed = 'left';
$leftFixedSetting['name']->width = '150px';
r($datatableTest->setFixedFieldWidthTest($leftFixedSetting)) && p('leftWidth,rightWidth') && e('280,0');

// 测试步骤3：只有右固定列的设置
$rightFixedSetting = array();
$rightFixedSetting['actions'] = new stdclass();
$rightFixedSetting['actions']->fixed = 'right';
$rightFixedSetting['actions']->width = '120px';
r($datatableTest->setFixedFieldWidthTest($rightFixedSetting)) && p('leftWidth,rightWidth') && e('30,120');

// 测试步骤4：左右都有固定列的设置
$bothFixedSetting = array();
$bothFixedSetting['id'] = new stdclass();
$bothFixedSetting['id']->fixed = 'left';
$bothFixedSetting['id']->width = '100px';
$bothFixedSetting['actions'] = new stdclass();
$bothFixedSetting['actions']->fixed = 'right';
$bothFixedSetting['actions']->width = '120px';
r($datatableTest->setFixedFieldWidthTest($bothFixedSetting)) && p('leftWidth,rightWidth') && e('130,120');

// 测试步骤5：包含auto宽度的固定列
$autoWidthSetting = array();
$autoWidthSetting['id'] = new stdclass();
$autoWidthSetting['id']->fixed = 'left';
$autoWidthSetting['id']->width = 'auto';
$autoWidthSetting['actions'] = new stdclass();
$autoWidthSetting['actions']->fixed = 'right';
$autoWidthSetting['actions']->width = 'auto';
r($datatableTest->setFixedFieldWidthTest($autoWidthSetting)) && p('leftWidth,rightWidth') && e('550,140');

// 测试步骤6：包含px后缀的宽度值
$pxSuffixSetting = array();
$pxSuffixSetting['title'] = new stdclass();
$pxSuffixSetting['title']->fixed = 'left';
$pxSuffixSetting['title']->width = '200px';
$pxSuffixSetting['status'] = new stdclass();
$pxSuffixSetting['status']->fixed = 'left';
$pxSuffixSetting['status']->width = '80px';
r($datatableTest->setFixedFieldWidthTest($pxSuffixSetting)) && p('leftWidth,rightWidth') && e('310,0');

// 测试步骤7：混合固定列和非固定列
$mixedSetting = array();
$mixedSetting['id'] = new stdclass();
$mixedSetting['id']->fixed = 'left';
$mixedSetting['id']->width = '100px';
$mixedSetting['title'] = new stdclass();
$mixedSetting['title']->fixed = 'no';
$mixedSetting['title']->width = '300px';
$mixedSetting['actions'] = new stdclass();
$mixedSetting['actions']->fixed = 'right';
$mixedSetting['actions']->width = '140px';
r($datatableTest->setFixedFieldWidthTest($mixedSetting)) && p('leftWidth,rightWidth') && e('130,140');
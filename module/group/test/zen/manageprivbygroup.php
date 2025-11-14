#!/usr/bin/env php
<?php

/**

title=测试 groupZen::managePrivByGroup();
timeout=0
cid=16734

- 步骤1：正常情况
 - 属性groupID @1
 - 属性nav @system
 - 属性version @~~
- 步骤2：空nav参数
 - 属性groupID @1
 - 属性nav @~~
- 步骤3：不存在的组ID
 - 属性groupID @999
 - 属性group @null
- 步骤4：带版本参数
 - 属性groupID @2
 - 属性nav @system
 - 属性version @1.0
- 步骤5：检查数据结构
 - 属性group @object
 - 属性title @string

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';

zenData('group')->loadYaml('group_manageprivbygroup', false, 2)->gen(5);
zenData('grouppriv')->loadYaml('grouppriv_manageprivbygroup', false, 2)->gen(32);

su('admin');

$groupZenTest = new groupZenTest();

r($groupZenTest->managePrivByGroupTest(1, 'system', '')) && p('groupID,nav,version') && e('1,system,~~'); // 步骤1：正常情况
r($groupZenTest->managePrivByGroupTest(1, '', '')) && p('groupID,nav') && e('1,~~'); // 步骤2：空nav参数
r($groupZenTest->managePrivByGroupTest(999, 'system', '')) && p('groupID,group') && e('999,null'); // 步骤3：不存在的组ID
r($groupZenTest->managePrivByGroupTest(2, 'system', '1.0')) && p('groupID,nav,version') && e('2,system,1.0'); // 步骤4：带版本参数
r($groupZenTest->managePrivByGroupTest(1, 'product', '')) && p('group,title') && e('object,string'); // 步骤5：检查数据结构
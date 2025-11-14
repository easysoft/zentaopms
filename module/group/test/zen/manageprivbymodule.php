#!/usr/bin/env php
<?php

/**

title=测试 groupZen::managePrivByModule();
timeout=0
cid=16735

- 步骤1:正常情况下调用方法,验证返回的视图数据结构完整性
 - 属性title @string
 - 属性groups @5
 - 属性subsets @76
 - 属性packages @76
 - 属性privs @18
- 步骤2:验证返回的title字段包含正确的字符串类型属性title @string
- 步骤3:验证返回的groups字段返回组数量属性groups @5
- 步骤4:验证返回的subsets字段是数组且包含子集信息属性subsets @76
- 步骤5:验证返回的packages字段是数组且包含包信息属性packages @76
- 步骤6:验证返回的privs字段是数组且包含权限信息属性privs @18

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';

zenData('group')->loadYaml('group_manageprivbygroup', false, 2)->gen(5);
zenData('grouppriv')->loadYaml('grouppriv_manageprivbygroup', false, 2)->gen(20);

su('admin');

$groupZenTest = new groupZenTest();

r($groupZenTest->managePrivByModuleTest()) && p('title,groups,subsets,packages,privs') && e('string,5,76,76,18'); // 步骤1:正常情况下调用方法,验证返回的视图数据结构完整性
r($groupZenTest->managePrivByModuleTest()) && p('title') && e('string'); // 步骤2:验证返回的title字段包含正确的字符串类型
r($groupZenTest->managePrivByModuleTest()) && p('groups') && e('5'); // 步骤3:验证返回的groups字段返回组数量
r($groupZenTest->managePrivByModuleTest()) && p('subsets') && e('76'); // 步骤4:验证返回的subsets字段是数组且包含子集信息
r($groupZenTest->managePrivByModuleTest()) && p('packages') && e('76'); // 步骤5:验证返回的packages字段是数组且包含包信息
r($groupZenTest->managePrivByModuleTest()) && p('privs') && e('18'); // 步骤6:验证返回的privs字段是数组且包含权限信息
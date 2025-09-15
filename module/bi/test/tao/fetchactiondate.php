#!/usr/bin/env php
<?php

/**

title=测试 biTao::fetchActionDate();
timeout=0
cid=0

- 步骤1：返回对象类型 @1
- 步骤2：包含minDate字段 @1
- 步骤3：包含maxDate字段 @1
- 步骤4：空数据库情况属性minDate @~~
- 步骤5：正常执行不报错属性maxDate @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r(is_object($biTest->fetchActionDateTest())) && p() && e('1');                    // 步骤1：返回对象类型
r(property_exists($biTest->fetchActionDateTest(), 'minDate')) && p() && e('1');   // 步骤2：包含minDate字段
r(property_exists($biTest->fetchActionDateTest(), 'maxDate')) && p() && e('1');   // 步骤3：包含maxDate字段
r($biTest->fetchActionDateTest()) && p('minDate') && e('~~');                     // 步骤4：空数据库情况
r($biTest->fetchActionDateTest()) && p('maxDate') && e('~~');                     // 步骤5：正常执行不报错
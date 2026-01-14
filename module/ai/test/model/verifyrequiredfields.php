#!/usr/bin/env php
<?php

/**

title=测试 aiModel::verifyRequiredFields();
timeout=0
cid=15081

- 返回false，无错误 @0
- 执行aiTest模块的verifyRequiredFieldsTest方法，参数是$requiredFields2, $postData2 属性name @『名称』不能为空。
- 执行aiTest模块的verifyRequiredFieldsTest方法，参数是$requiredFields3, $postData3
 - 属性name @『名称』不能为空。
 - 属性email @『邮箱』不能为空。
- 执行aiTest模块的verifyRequiredFieldsTest方法，参数是$requiredFields4, $postData4 属性missing_field @『缺失字段』不能为空。
- 执行aiTest模块的verifyRequiredFieldsTest方法，参数是$requiredFields5, $postData5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

// 测试步骤1：正常情况 - 所有必填字段都已填写
$requiredFields1 = array('name' => '名称', 'email' => '邮箱');
$postData1 = array('name' => 'testuser', 'email' => 'test@example.com');
r($aiTest->verifyRequiredFieldsTest($requiredFields1, $postData1)) && p() && e('0'); // 返回false，无错误

// 测试步骤2：单个字段为空字符串
$requiredFields2 = array('name' => '名称', 'email' => '邮箱');
$postData2 = array('name' => '', 'email' => 'test@example.com');
r($aiTest->verifyRequiredFieldsTest($requiredFields2, $postData2)) && p('name') && e('『名称』不能为空。');

// 测试步骤3：多个字段为空或缺失
$requiredFields3 = array('name' => '名称', 'email' => '邮箱', 'phone' => '电话');
$postData3 = array('name' => '', 'email' => '', 'phone' => 'validphone');
r($aiTest->verifyRequiredFieldsTest($requiredFields3, $postData3)) && p('name,email') && e('『名称』不能为空。,『邮箱』不能为空。');

// 测试步骤4：必填字段在POST中不存在
$requiredFields4 = array('name' => '名称', 'missing_field' => '缺失字段');
$postData4 = array('name' => 'testuser');
r($aiTest->verifyRequiredFieldsTest($requiredFields4, $postData4)) && p('missing_field') && e('『缺失字段』不能为空。');

// 测试步骤5：边界情况 - 空的必填字段数组
$requiredFields5 = array();
$postData5 = array('name' => 'testuser');
r($aiTest->verifyRequiredFieldsTest($requiredFields5, $postData5)) && p() && e('0');
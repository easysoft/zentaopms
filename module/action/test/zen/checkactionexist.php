#!/usr/bin/env php
<?php

/**

title=测试 actionZen::checkActionExist();
timeout=0
cid=14969

- 步骤1：检查存在的操作记录
 - 属性id @1
 - 属性objectType @product
- 步骤2：检查不存在的操作记录
 - 属性result @fail
 - 属性message @抱歉，您访问的对象不存在！
- 步骤3：检查无效ID为0
 - 属性result @fail
 - 属性message @抱歉，您访问的对象不存在！
- 步骤4：检查负数ID
 - 属性result @fail
 - 属性message @抱歉，您访问的对象不存在！
- 步骤5：检查极大ID
 - 属性result @fail
 - 属性message @抱歉，您访问的对象不存在！

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('action')->loadYaml('action_checkactionexist', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($actionTest->checkActionExistTest(1))     && p('id,objectType')  && e('1,product');                       // 步骤1：检查存在的操作记录
r($actionTest->checkActionExistTest(999))   && p('result,message') && e('fail,抱歉，您访问的对象不存在！'); // 步骤2：检查不存在的操作记录
r($actionTest->checkActionExistTest(0))     && p('result,message') && e('fail,抱歉，您访问的对象不存在！'); // 步骤3：检查无效ID为0
r($actionTest->checkActionExistTest(-1))    && p('result,message') && e('fail,抱歉，您访问的对象不存在！'); // 步骤4：检查负数ID
r($actionTest->checkActionExistTest(99999)) && p('result,message') && e('fail,抱歉，您访问的对象不存在！'); // 步骤5：检查极大ID
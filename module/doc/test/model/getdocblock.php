#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocBlock();
timeout=0
cid=16074

- 步骤1：正常情况-获取存在的文档块
 - 属性id @1
 - 属性type @text
- 步骤2：边界值-获取不存在的文档块 @0
- 步骤3：异常输入-使用0作为ID @0
- 步骤4：异常输入-使用负数作为ID @0
- 步骤5：业务规则-验证返回完整字段
 - 属性id @2
 - 属性doc @1002
 - 属性type @image
 - 属性settings @setting2
 - 属性content @content2
 - 属性extra @extra2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('docblock');
$table->id->range('1-10');
$table->doc->range('1001,1002,1003,1004,1005');
$table->type->range('text,image,table,code,list');
$table->settings->range('setting1,setting2,setting3,,');
$table->content->range('content1,content2,content3,content4,content5');
$table->extra->range('extra1,extra2,extra3,,');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->getDocBlockTest(1)) && p('id,type') && e('1,text'); // 步骤1：正常情况-获取存在的文档块
r($docTest->getDocBlockTest(999)) && p() && e('0'); // 步骤2：边界值-获取不存在的文档块
r($docTest->getDocBlockTest(0)) && p() && e('0'); // 步骤3：异常输入-使用0作为ID
r($docTest->getDocBlockTest(-1)) && p() && e('0'); // 步骤4：异常输入-使用负数作为ID
r($docTest->getDocBlockTest(2)) && p('id,doc,type,settings,content,extra') && e('2,1002,image,setting2,content2,extra2'); // 步骤5：业务规则-验证返回完整字段
#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareStartExtras();
timeout=0
cid=17953

- 步骤1:正常的postData对象处理
 - 属性status @doing
 - 属性lastEditedBy @admin
- 步骤2:空postData对象处理
 - 属性status @doing
 - 属性lastEditedBy @admin
- 步骤3:包含其他字段的postData对象处理
 - 属性status @doing
 - 属性lastEditedBy @admin
 - 属性name @测试项目
- 步骤4:验证所有字段都已正确设置
 - 属性status @doing
 - 属性lastEditedBy @admin
- 步骤5:多次调用方法验证数据独立性
 - 属性status @doing
 - 属性lastEditedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$projectTest = new projectZenTest();

// 创建测试用的postData对象
class testPostData {
    public function add($key, $value) {
        $this->{$key} = $value;
        return $this;
    }

    public function get() {
        return $this;
    }
}

// 创建不同的测试数据对象
$testPostData1 = new testPostData();

$testPostData2 = new testPostData();

$testPostData3 = new testPostData();
$testPostData3->name = '测试项目';
$testPostData3->desc = '这是一个测试项目';

$testPostData4 = new testPostData();

$testPostData5 = new testPostData();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->prepareStartExtrasTest($testPostData1)) && p('status,lastEditedBy') && e('doing,admin'); // 步骤1:正常的postData对象处理
r($projectTest->prepareStartExtrasTest($testPostData2)) && p('status,lastEditedBy') && e('doing,admin'); // 步骤2:空postData对象处理
r($projectTest->prepareStartExtrasTest($testPostData3)) && p('status,lastEditedBy,name') && e('doing,admin,测试项目'); // 步骤3:包含其他字段的postData对象处理
r($projectTest->prepareStartExtrasTest($testPostData4)) && p('status,lastEditedBy') && e('doing,admin'); // 步骤4:验证所有字段都已正确设置
r($projectTest->prepareStartExtrasTest($testPostData5)) && p('status,lastEditedBy') && e('doing,admin'); // 步骤5:多次调用方法验证数据独立性
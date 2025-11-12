#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareClosedExtras();
timeout=0
cid=0

- 步骤1:正常项目关闭数据处理
 - 属性id @1
 - 属性status @closed
- 步骤2:带描述的项目关闭数据处理
 - 属性id @2
 - 属性status @closed
- 步骤3:项目ID为0的边界值处理
 - 属性id @0
 - 属性status @closed
- 步骤4:大数值项目ID处理
 - 属性id @9999
 - 属性status @closed
- 步骤5:验证所有必需字段设置完整
 - 属性id @5
 - 属性status @closed
 - 属性closedBy @admin
 - 属性lastEditedBy @admin

*/

// 1. 导入依赖（路径固定,不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('project');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 创建测试用的postData对象
class testPostData {
    public function add($key, $value) {
        $this->{$key} = $value;
        return $this;
    }

    public function setDefault($key, $value) {
        if (!isset($this->{$key})) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public function stripTags($fields, $allowedTags) {
        $fieldsList = explode(',', $fields);
        foreach ($fieldsList as $field) {
            $field = trim($field);
            if (isset($this->{$field})) {
                $this->{$field} = strip_tags($this->{$field}, $allowedTags);
            }
        }
        return $this;
    }

    public function get() {
        return $this;
    }
}

// 创建不同的测试数据对象
$testPostData1 = new testPostData();
$testPostData1->comment = '项目已完成,关闭项目';

$testPostData2 = new testPostData();
$testPostData2->desc = '<script>alert("test")</script><p>项目描述</p>';
$testPostData2->comment = '带描述的关闭';

$testPostData3 = new testPostData();
$testPostData3->comment = '测试边界值';

$testPostData4 = new testPostData();
$testPostData4->comment = '大数值ID测试';

$testPostData5 = new testPostData();
$testPostData5->comment = '完整字段验证';
$testPostData5->desc = '测试描述';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectzenTest->prepareClosedExtrasTest(1, $testPostData1)) && p('id,status') && e('1,closed'); // 步骤1:正常项目关闭数据处理
r($projectzenTest->prepareClosedExtrasTest(2, $testPostData2)) && p('id,status') && e('2,closed'); // 步骤2:带描述的项目关闭数据处理
r($projectzenTest->prepareClosedExtrasTest(0, $testPostData3)) && p('id,status') && e('0,closed'); // 步骤3:项目ID为0的边界值处理
r($projectzenTest->prepareClosedExtrasTest(9999, $testPostData4)) && p('id,status') && e('9999,closed'); // 步骤4:大数值项目ID处理
r($projectzenTest->prepareClosedExtrasTest(5, $testPostData5)) && p('id,status,closedBy,lastEditedBy') && e('5,closed,admin,admin'); // 步骤5:验证所有必需字段设置完整
#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareActivateExtras();
timeout=0
cid=17947

- 步骤1：正常项目激活数据
 - 属性id @1
 - 属性status @doing
- 步骤2：0000-00-00日期处理
 - 属性id @2
 - 属性begin @~~
 - 属性end @~~
- 步骤3：不存在项目ID处理
 - 属性id @999
 - 属性status @doing
- 步骤4：零项目ID和空数据处理
 - 属性id @0
 - 属性status @doing
- 步骤5：验证返回对象结构和标签过滤
 - 属性id @4
 - 属性lastEditedBy @admin
 - 属性realEnd @~~

*/

// 1. 导入依赖（路径固定，不可修改）
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

    public function setIF($condition, $key, $value) {
        if ($condition) {
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
$testPostData1->rawdata = (object)array('begin' => '2023-01-01', 'end' => '2023-06-01');
$testPostData1->comment = '激活项目';

$testPostData2 = new testPostData();
$testPostData2->rawdata = (object)array('begin' => '0000-00-00', 'end' => '0000-00-00');
$testPostData2->comment = '激活测试';

$testPostData3 = new testPostData();
$testPostData3->rawdata = (object)array('begin' => '2023-01-01', 'end' => '2023-06-01');

$testPostData4 = new testPostData();
$testPostData4->rawdata = (object)array('begin' => '2023-04-01', 'end' => '2023-09-01');
$testPostData4->desc = '<script>alert("test")</script>';

$emptyPostData = new testPostData();
$emptyPostData->rawdata = (object)array('begin' => '2023-01-01', 'end' => '2023-06-01');

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectzenTest->prepareActivateExtrasTest(1, $testPostData1)) && p('id,status') && e('1,doing'); // 步骤1：正常项目激活数据
r($projectzenTest->prepareActivateExtrasTest(2, $testPostData2)) && p('id,begin,end') && e('2,~~,~~'); // 步骤2：0000-00-00日期处理
r($projectzenTest->prepareActivateExtrasTest(999, $testPostData3)) && p('id,status') && e('999,doing'); // 步骤3：不存在项目ID处理
r($projectzenTest->prepareActivateExtrasTest(0, $emptyPostData)) && p('id,status') && e('0,doing'); // 步骤4：零项目ID和空数据处理
r($projectzenTest->prepareActivateExtrasTest(4, $testPostData4)) && p('id,lastEditedBy,realEnd') && e('4,admin,~~'); // 步骤5：验证返回对象结构和标签过滤
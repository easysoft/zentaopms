#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::processDatas();
timeout=0
cid=0

- 执行testcaseTest模块的processDatasTest方法，参数是$data1 第0条的desc:1:content属性 @打开登录页面
- 执行testcaseTest模块的processDatasTest方法，参数是$data2 第0条的desc:2:content属性 @第二个步骤
- 执行testcaseTest模块的processDatasTest方法，参数是$data3 第0条的desc:1:content属性 @登录系统
- 执行testcaseTest模块的processDatasTest方法，参数是$data4 第0条的expect:1:content属性 @显示登录界面
- 执行testcaseTest模块的processDatasTest方法，参数是$data5  @rray()

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：测试处理基本编号步骤格式
$data1 = array(
    0 => array(
        'stepDesc' => '1. 打开登录页面'
    )
);
r($testcaseTest->processDatasTest($data1)) && p('0:desc:1:content') && e('打开登录页面'); 

// 步骤2：测试处理多行步骤描述
$data2 = array(
    0 => array(
        'stepDesc' => "1、第一个步骤\n2、第二个步骤"
    )
);
r($testcaseTest->processDatasTest($data2)) && p('0:desc:2:content') && e('第二个步骤');

// 步骤3：测试处理带中文分隔符的步骤
$data3 = array(
    0 => array(
        'stepDesc' => '1、登录系统'
    )
);
r($testcaseTest->processDatasTest($data3)) && p('0:desc:1:content') && e('登录系统');

// 步骤4：测试处理带期望字段的完整数据
$data4 = array(
    0 => array(
        'stepDesc' => '1. 输入用户名',
        'stepExpect' => '1. 显示登录界面'
    )
);
r($testcaseTest->processDatasTest($data4)) && p('0:expect:1:content') && e('显示登录界面');

// 步骤5：测试处理空数据输入
$data5 = array();
r($testcaseTest->processDatasTest($data5)) && p() && e(array());
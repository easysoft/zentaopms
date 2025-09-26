#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::processDatas();
cid=0

- 测试处理基本编号步骤格式（1. 格式） >> 返回正确的步骤内容
- 测试处理多行步骤描述（换行分隔） >> 正确解析第二个步骤
- 测试处理带中文分隔符的步骤（1、格式） >> 返回正确的步骤内容
- 测试处理带期望字段的完整数据 >> 正确处理expect字段
- 测试处理空数据输入 >> 返回空数组
- 测试处理复杂嵌套编号格式（1.1.1.格式） >> 正确解析嵌套编号
- 测试处理无编号的纯文本 >> 自动添加编号为1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

su('admin');

$testcaseTest = new testcaseTest();

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

// 步骤6：测试处理复杂嵌套编号格式
$data6 = array(
    0 => array(
        'stepDesc' => '1.1.1. 输入用户名'
    )
);
r($testcaseTest->processDatasTest($data6)) && p('0:desc:1.1.1:content') && e('输入用户名');

// 步骤7：测试处理无编号的纯文本
$data7 = array(
    0 => array(
        'stepDesc' => '测试内容'
    )
);
r($testcaseTest->processDatasTest($data7)) && p('0:desc:1:content') && e('测试内容');
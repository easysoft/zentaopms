#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel->processDatas();
timeout=0
cid=19015

- 测试处理包含单级步骤编号的数据 @1
- 测试处理包含二级步骤编号的数据 @1
- 测试处理包含三级步骤编号的数据 @1
- 测试处理包含换行符的步骤数据 @1
- 测试处理无编号的步骤描述 @1
- 测试处理空步骤数据 @1
- 测试处理包含中文顿号分隔的步骤 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

su('admin');

$testcase = new testcaseTest();

// 测试用例1: 单级步骤编号
$data1 = array(
    0 => (object)array(
        'stepDesc'   => "1.登录系统\n2.打开产品列表",
        'stepExpect' => "1.登录成功\n2.显示产品列表"
    )
);

// 测试用例2: 二级步骤编号
$data2 = array(
    0 => (object)array(
        'stepDesc'   => "1.打开页面\n1.1.输入用户名\n1.2.输入密码\n2.点击登录",
        'stepExpect' => "1.页面打开成功\n1.1.用户名输入成功\n1.2.密码输入成功\n2.登录成功"
    )
);

// 测试用例3: 三级步骤编号
$data3 = array(
    0 => (object)array(
        'stepDesc'   => "1.打开页面\n1.1.验证表单\n1.1.1.验证用户名\n1.1.2.验证密码\n1.2.提交表单\n2.查看结果",
        'stepExpect' => "1.页面打开\n1.1.表单验证\n1.1.1.用户名正确\n1.1.2.密码正确\n1.2.表单提交\n2.显示结果"
    )
);

// 测试用例4: 使用\r作为换行符
$data4 = array(
    0 => (object)array(
        'stepDesc'   => "1.第一步\r2.第二步\r3.第三步",
        'stepExpect' => "1.期望结果1\r2.期望结果2\r3.期望结果3"
    )
);

// 测试用例5: 无编号的步骤描述
$data5 = array(
    0 => (object)array(
        'stepDesc'   => "登录系统并验证",
        'stepExpect' => "登录成功"
    )
);

// 测试用例6: 空数据
$data6 = array(
    0 => (object)array(
        'stepDesc'   => "",
        'stepExpect' => ""
    )
);

// 测试用例7: 使用中文顿号分隔
$data7 = array(
    0 => (object)array(
        'stepDesc'   => "1、打开浏览器\n2、访问网站\n3、登录账号",
        'stepExpect' => "1、浏览器打开\n2、网站访问成功\n3、账号登录成功"
    )
);

r(count($testcase->processDatasTest($data1))) && p() && e('1'); // 测试处理包含单级步骤编号的数据
r(count($testcase->processDatasTest($data2))) && p() && e('1'); // 测试处理包含二级步骤编号的数据
r(count($testcase->processDatasTest($data3))) && p() && e('1'); // 测试处理包含三级步骤编号的数据
r(count($testcase->processDatasTest($data4))) && p() && e('1'); // 测试处理包含换行符的步骤数据
r(count($testcase->processDatasTest($data5))) && p() && e('1'); // 测试处理无编号的步骤描述
r(count($testcase->processDatasTest($data6))) && p() && e('1'); // 测试处理空步骤数据
r(count($testcase->processDatasTest($data7))) && p() && e('1'); // 测试处理包含中文顿号分隔的步骤
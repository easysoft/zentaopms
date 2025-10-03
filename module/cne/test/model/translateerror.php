#!/usr/bin/env php
<?php

/**

title=测试 cneModel::translateError();
timeout=0
cid=0

- 执行cneTest模块的translateErrorTest方法，参数是$apiResult1 属性code @400
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult2 属性code @404
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult3 属性code @999
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult4 属性code @41001
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult5 属性code @40004

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

try {
    include $initPath;
    include $unitTestPath;
    su('admin');
    $useFramework = true;
} catch (Exception $e) {
    // 框架初始化失败，使用独立模式
    include $unitTestPath;
    $useFramework = false;
}

$cneTest = new cneModelTest();

// 测试步骤1:已知错误代码400
$apiResult1 = new stdclass();
$apiResult1->code = 400;
$apiResult1->message = 'original error';
r($cneTest->translateErrorTest($apiResult1)) && p('code') && e('400');

// 测试步骤2:已知错误代码404
$apiResult2 = new stdclass();
$apiResult2->code = 404;
$apiResult2->message = 'not found';
r($cneTest->translateErrorTest($apiResult2)) && p('code') && e('404');

// 测试步骤3:未知错误代码999
$apiResult3 = new stdclass();
$apiResult3->code = 999;
$apiResult3->message = 'unknown error';
r($cneTest->translateErrorTest($apiResult3)) && p('code') && e('999');

// 测试步骤4:已知错误代码41001
$apiResult4 = new stdclass();
$apiResult4->code = 41001;
$apiResult4->message = 'certificate expired';
r($cneTest->translateErrorTest($apiResult4)) && p('code') && e('41001');

// 测试步骤5:已知错误代码40004
$apiResult5 = new stdclass();
$apiResult5->code = 40004;
$apiResult5->message = 'cert domain mismatch';
r($cneTest->translateErrorTest($apiResult5)) && p('code') && e('40004');
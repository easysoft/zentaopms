#!/usr/bin/env php
<?php

/**

title=测试 companyZen::saveUriIntoSession();
timeout=0
cid=15742

- 步骤1:不设置URI时调用方法成功 @1
- 步骤2:设置URI为/product时调用方法成功 @1
- 步骤3:设置URI为/project时调用方法成功 @1
- 步骤4:设置URI为/task时调用方法成功 @1
- 步骤5:设置URI为/bug时调用方法成功 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
zenData('user')->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$companyTest = new companyZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($companyTest->saveUriIntoSessionTest('')) && p() && e('1'); // 步骤1:不设置URI时调用方法成功
r($companyTest->saveUriIntoSessionTest('/product-browse-1.html')) && p() && e('1'); // 步骤2:设置URI为/product时调用方法成功
r($companyTest->saveUriIntoSessionTest('/project-browse-1.html')) && p() && e('1'); // 步骤3:设置URI为/project时调用方法成功
r($companyTest->saveUriIntoSessionTest('/task-browse-1.html')) && p() && e('1'); // 步骤4:设置URI为/task时调用方法成功
r($companyTest->saveUriIntoSessionTest('/bug-browse-1.html')) && p() && e('1'); // 步骤5:设置URI为/bug时调用方法成功
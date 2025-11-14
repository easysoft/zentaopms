#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::includeTable();
timeout=0
cid=15957

- 步骤1：加载存在的bug表配置文件
 - 属性primaryTable @bug
 - 第tables条的bug属性 @zt_bug
- 步骤2：加载存在的product表配置文件
 - 属性primaryTable @product
 - 第tables条的product属性 @zt_product
- 步骤3：加载存在的task表配置文件
 - 属性primaryTable @task
 - 第tables条的task属性 @zt_task
- 步骤4：加载不存在的表配置文件 @0
- 步骤5：使用空字符串作为表名 @0
- 步骤6：验证返回的schema对象主表名属性primaryTable @bug

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dataview.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$dataviewTest = new dataviewTest();

// 4. 测试步骤执行
r($dataviewTest->includeTableTest('bug')) && p('primaryTable;tables:bug') && e('bug,zt_bug'); // 步骤1：加载存在的bug表配置文件
r($dataviewTest->includeTableTest('product')) && p('primaryTable;tables:product') && e('product,zt_product'); // 步骤2：加载存在的product表配置文件
r($dataviewTest->includeTableTest('task')) && p('primaryTable;tables:task') && e('task,zt_task'); // 步骤3：加载存在的task表配置文件
r($dataviewTest->includeTableTest('nonexistent_table')) && p() && e('0'); // 步骤4：加载不存在的表配置文件
r($dataviewTest->includeTableTest('')) && p() && e('0'); // 步骤5：使用空字符串作为表名
r($dataviewTest->includeTableTest('bug')) && p('primaryTable') && e('bug'); // 步骤6：验证返回的schema对象主表名
#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::includeTable();
timeout=0
cid=0

- 步骤1：加载存在的bug表配置文件
 - 属性primaryTable @bug
 - 第tables条的bug属性 @zt_bug
- 步骤2：加载存在的product表配置文件
 - 属性primaryTable @product
 - 第tables条的product属性 @zt_product
- 步骤3：加载存在的task表配置文件
 - 属性primaryTable @task
 - 第tables条的task属性 @zt_task
- 步骤4：加载不存在的表配置文件 @null
- 步骤5：使用空字符串作为表名 @null
- 步骤6：验证返回的schema对象字段结构
 - 第fields条的id:type属性 @number
 - 第fields条的title:type属性 @string
 - 第fields条的status:type属性 @option

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';

// 2. 用户登录
su('admin');

// 3. 加载模型
global $tester;
$tester->loadModel('dataview');

// 4. 测试步骤执行
r($tester->dataview->includeTable('bug')) && p('primaryTable;tables:bug') && e('bug,zt_bug'); // 步骤1：加载存在的bug表配置文件
r($tester->dataview->includeTable('product')) && p('primaryTable;tables:product') && e('product,zt_product'); // 步骤2：加载存在的product表配置文件
r($tester->dataview->includeTable('task')) && p('primaryTable;tables:task') && e('task,zt_task'); // 步骤3：加载存在的task表配置文件
r($tester->dataview->includeTable('nonexistent_table')) && p() && e('null'); // 步骤4：加载不存在的表配置文件
r($tester->dataview->includeTable('')) && p() && e('null'); // 步骤5：使用空字符串作为表名
r($tester->dataview->includeTable('bug')) && p('fields:id:type;fields:title:type;fields:status:type') && e('number,string,option'); // 步骤6：验证返回的schema对象字段结构
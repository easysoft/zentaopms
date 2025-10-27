#!/usr/bin/env php
<?php

/**

title=测试 chartModel::__construct();
timeout=0
cid=0

- 步骤1：正常情况测试 @success
- 步骤2：模型实例存在 @true
- 步骤3：实例类型验证 @true
- 步骤4：父类构造函数调用 @true
- 步骤5：bi模型加载验证 @true
- 步骤6：DAO加载验证 @true
- 步骤7：loadModel功能验证 @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备
zenData('chart')->loadYaml('chart')->gen(5);
zenData('module')->loadYaml('module')->gen(5);
zenData('user')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$chart = new chartTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($chart->__constructTest('normal')) && p('result') && e('normal'); // 步骤1：正常情况测试
r($chart->__constructTest('modelExists')) && p('result') && e('1'); // 步骤2：模型实例存在
r($chart->__constructTest('modelInstance')) && p('result') && e('1'); // 步骤3：实例类型验证
r($chart->__constructTest('className')) && p('result') && e('chartModel'); // 步骤4：类名验证
r($chart->__constructTest('parentConstructor')) && p('result') && e('1'); // 步骤5：父类构造函数调用
r($chart->__constructTest('dao')) && p('result') && e('1'); // 步骤6：DAO加载验证
r($chart->__constructTest('biModel')) && p('result') && e('1'); // 步骤7：bi模型加载验证
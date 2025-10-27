#!/usr/bin/env php
<?php

/**

title=测试 docZen::formFromSession();
timeout=0
cid=0

- 步骤1：正常情况测试存在的session数据的URL @http://test.com/product
- 步骤2：空字符串参数测试返回空URL @~~
- 步骤3：不存在的类型参数返回空URL @~~
- 步骤4：特殊字符类型参数返回空URL @~~
- 步骤5：数字字符串类型参数返回空URL @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. 不需要zendata数据准备，因为这个方法主要处理session数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况测试存在的session数据
$_SESSION['zentaoListProduct'] = array(
    'url' => 'http://test.com/product',
    'idList' => '1,2,3',
    'cols' => array('id', 'name', 'status'),
    'data' => array('product1', 'product2', 'product3')
);
r($docTest->formFromSessionTest('Product')) && p('0') && e('http://test.com/product'); // 步骤1：正常情况测试存在的session数据的URL

// 步骤2：空字符串参数测试
r($docTest->formFromSessionTest('')) && p('0') && e('~~'); // 步骤2：空字符串参数测试返回空URL

// 步骤3：不存在的类型参数测试
r($docTest->formFromSessionTest('NonExistent')) && p('0') && e('~~'); // 步骤3：不存在的类型参数返回空URL

// 步骤4：特殊字符类型参数测试
r($docTest->formFromSessionTest('Special@#$%')) && p('0') && e('~~'); // 步骤4：特殊字符类型参数返回空URL

// 步骤5：数字字符串类型参数测试
r($docTest->formFromSessionTest('123')) && p('0') && e('~~'); // 步骤5：数字字符串类型参数返回空URL
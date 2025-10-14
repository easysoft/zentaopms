#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterMove();
timeout=0
cid=0

- 步骤1：正常文档移动
 - 属性result @success
 - 属性closeModal @1
- 步骤2：空间类型改变到我的空间
 - 属性result @success
 - 属性load @/doc-mySpace-1-1.html
- 步骤3：空间类型改变到团队空间
 - 属性result @success
 - 属性load @/doc-teamSpace-2-2.html
- 步骤4：空间类型改变到产品空间
 - 属性result @success
 - 属性load @/doc-productSpace-3-3.html
- 步骤5：空间类型未改变
 - 属性result @success
 - 属性closeModal @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('doclib')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->responseAfterMoveTest('mine.1', 1, 1, false)) && p('result,closeModal') && e('success,1'); // 步骤1：正常文档移动
r($docTest->responseAfterMoveTest('mine.1', 1, 0, true)) && p('result,load') && e('success,/doc-mySpace-1-1.html'); // 步骤2：空间类型改变到我的空间  
r($docTest->responseAfterMoveTest('custom.2', 2, 0, true)) && p('result,load') && e('success,/doc-teamSpace-2-2.html'); // 步骤3：空间类型改变到团队空间
r($docTest->responseAfterMoveTest('product.3', 3, 0, true)) && p('result,load') && e('success,/doc-productSpace-3-3.html'); // 步骤4：空间类型改变到产品空间
r($docTest->responseAfterMoveTest('mine.1', 1, 0, false)) && p('result,closeModal') && e('success,1'); // 步骤5：空间类型未改变
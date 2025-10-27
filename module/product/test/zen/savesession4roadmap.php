#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveSession4Roadmap();
timeout=0
cid=0

- 步骤1：正常情况属性product_releaseList @/product/roadmap/
- 步骤2：另一个URI属性product_releaseList @/empty/test/
- 步骤3：自定义URI属性product_releaseList @/custom/path/
- 步骤4：验证productPlanList属性product_productPlanList @/test/uri/
- 步骤5：完整验证
 - 属性product_releaseList @/final/test/
 - 属性product_productPlanList @/final/test/

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->saveSession4RoadmapTest('/product/roadmap/')) && p('product_releaseList') && e('/product/roadmap/'); // 步骤1：正常情况
r($productTest->saveSession4RoadmapTest('/empty/test/')) && p('product_releaseList') && e('/empty/test/'); // 步骤2：另一个URI
r($productTest->saveSession4RoadmapTest('/custom/path/')) && p('product_releaseList') && e('/custom/path/'); // 步骤3：自定义URI
r($productTest->saveSession4RoadmapTest('/test/uri/')) && p('product_productPlanList') && e('/test/uri/'); // 步骤4：验证productPlanList
r($productTest->saveSession4RoadmapTest('/final/test/')) && p('product_releaseList,product_productPlanList') && e('/final/test/,/final/test/'); // 步骤5：完整验证
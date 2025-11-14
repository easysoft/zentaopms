#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveSession4Browse();
timeout=0
cid=17608

- 测试在product模块下保存session,产品类型为normal属性currentProductType @normal
- 测试在product模块下保存session,产品类型为branch属性currentProductType @branch
- 测试在product模块下保存session,浏览类型为unclosed属性storyBrowseType @unclosed
- 测试在product模块下保存session,浏览类型为all属性storyBrowseType @all
- 测试在project模块下保存session,产品类型为normal属性currentProductType @normal
- 测试浏览类型为bysearch时保存storyBrowseType属性storyBrowseType @bysearch
- 测试浏览类型为active时保存storyBrowseType属性storyBrowseType @active
- 测试产品类型为platform属性currentProductType @platform

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product1 = new stdclass();
$product1->id   = 1;
$product1->name = 'Product 1';
$product1->type = 'normal';

$product2 = new stdclass();
$product2->id   = 2;
$product2->name = 'Product 2';
$product2->type = 'branch';

$product3 = new stdclass();
$product3->id   = 3;
$product3->name = 'Product 3';
$product3->type = 'platform';

su('admin');

$productTest = new productZenTest();

r($productTest->saveSession4BrowseTest($product1, 'unclosed', 'product')) && p('currentProductType') && e('normal'); // 测试在product模块下保存session,产品类型为normal
r($productTest->saveSession4BrowseTest($product2, 'all', 'product')) && p('currentProductType') && e('branch'); // 测试在product模块下保存session,产品类型为branch
r($productTest->saveSession4BrowseTest($product1, 'unclosed', 'product')) && p('storyBrowseType') && e('unclosed'); // 测试在product模块下保存session,浏览类型为unclosed
r($productTest->saveSession4BrowseTest($product1, 'all', 'product')) && p('storyBrowseType') && e('all'); // 测试在product模块下保存session,浏览类型为all
r($productTest->saveSession4BrowseTest($product1, 'active', 'project')) && p('currentProductType') && e('normal'); // 测试在project模块下保存session,产品类型为normal
r($productTest->saveSession4BrowseTest($product1, 'bysearch', 'product')) && p('storyBrowseType') && e('bysearch'); // 测试浏览类型为bysearch时保存storyBrowseType
r($productTest->saveSession4BrowseTest($product1, 'active', 'product')) && p('storyBrowseType') && e('active'); // 测试浏览类型为active时保存storyBrowseType
r($productTest->saveSession4BrowseTest($product3, 'unclosed', 'product')) && p('currentProductType') && e('platform'); // 测试产品类型为platform
#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildSearchFormForBrowse();
timeout=0
cid=0

- 测试步骤1:正常产品浏览属性productID @1
- 测试步骤2:验证搜索模块类型属性searchModule @story
- 测试步骤3:验证产品字段存在属性hasProductField @1
- 测试步骤4:requirement类型属性searchModule @requirement
- 测试步骤5:按搜索浏览属性productID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('project')->loadYaml('project', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

$productTest = new productZenTest();

r($productTest->buildSearchFormForBrowseTest(null, 0, 1, '', 0, 'story', 'all', false, '', 0)) && p('productID') && e('1'); // 测试步骤1:正常产品浏览
r($productTest->buildSearchFormForBrowseTest(null, 0, 1, '', 0, 'story', 'all', false, '', 0)) && p('searchModule') && e('story'); // 测试步骤2:验证搜索模块类型
r($productTest->buildSearchFormForBrowseTest(null, 0, 1, '', 0, 'story', 'all', false, '', 0)) && p('hasProductField') && e('1'); // 测试步骤3:验证产品字段存在
r($productTest->buildSearchFormForBrowseTest(null, 0, 1, '', 0, 'requirement', 'all', false, '', 0)) && p('searchModule') && e('requirement'); // 测试步骤4:requirement类型
r($productTest->buildSearchFormForBrowseTest(null, 0, 1, '', 0, 'story', 'bysearch', false, '', 0)) && p('productID') && e('1'); // 测试步骤5:按搜索浏览
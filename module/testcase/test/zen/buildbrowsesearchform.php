#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildBrowseSearchForm();
timeout=0
cid=0

- 测试步骤1：正常情况下构建搜索表单
 - 属性onMenuBar @yes
 - 属性searchProductsCount @5
- 测试步骤2：非testcase模块不设置onMenuBar属性onMenuBar @~~
- 测试步骤3：不同productID的搜索表单构建
 - 属性onMenuBar @yes
 - 属性searchProductsCount @5
- 测试步骤4：不同projectID的搜索表单构建
 - 属性onMenuBar @yes
 - 属性searchFieldsCount @20
- 测试步骤5：不同queryID的搜索表单构建
 - 属性onMenuBar @yes
 - 属性searchFieldsCount @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('product')->gen(5);

su('admin');

$testcase = new testcaseTest();

r($testcase->buildBrowseSearchFormTest(1, 0, 1, '/testcase-browse-1.html', 'testcase')) && p('onMenuBar,searchProductsCount') && e('yes,5'); // 测试步骤1：正常情况下构建搜索表单
r($testcase->buildBrowseSearchFormTest(1, 0, 1, '/testcase-browse-1.html', 'other')) && p('onMenuBar') && e('~~'); // 测试步骤2：非testcase模块不设置onMenuBar
r($testcase->buildBrowseSearchFormTest(2, 0, 1, '/testcase-browse-2.html', 'testcase')) && p('onMenuBar,searchProductsCount') && e('yes,5'); // 测试步骤3：不同productID的搜索表单构建
r($testcase->buildBrowseSearchFormTest(1, 1, 2, '/testcase-browse-1.html', 'testcase')) && p('onMenuBar,searchFieldsCount') && e('yes,20'); // 测试步骤4：不同projectID的搜索表单构建
r($testcase->buildBrowseSearchFormTest(1, 2, 1, '/testcase-browse-1.html', 'testcase')) && p('onMenuBar,searchFieldsCount') && e('yes,20'); // 测试步骤5：不同queryID的搜索表单构建
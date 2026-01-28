#!/usr/bin/env php
<?php

/**

title=测试 executionModel::buildCaseSearchForm();
timeout=0
cid=16270

- 执行executionTest模块的buildCaseSearchFormTest方法，参数是$normalProducts, 1, '/execution-testcase-1.html', 1  @executionCase
- 执行executionTest模块的buildCaseSearchFormTest方法，参数是$normalProducts, 2, '/execution-testcase-2.html', 2  @executionCase
- 执行executionTest模块的buildCaseSearchFormTest方法，参数是$normalProducts, 3, '/execution-testcase-3.html', 3  @executionCase
- 执行executionTest模块的buildCaseSearchFormTest方法，参数是$emptyProducts, 0, '/execution-testcase-0.html', 4  @executionCase
- 执行executionTest模块的buildCaseSearchFormTest方法，参数是$normalProducts, 0, '/execution-testcase-boundary.html', 1  @executionCase

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$executionTest = new executionModelTest();

$normalProducts = array();
$product1 = new stdclass();
$product1->id = 1;
$product1->name = 'Product1';
$product1->type = 'normal';
$normalProducts[1] = $product1;

$emptyProducts = array();

r($executionTest->buildCaseSearchFormTest($normalProducts, 1, '/execution-testcase-1.html', 1)) && p() && e('executionCase');
r($executionTest->buildCaseSearchFormTest($normalProducts, 2, '/execution-testcase-2.html', 2)) && p() && e('executionCase');
r($executionTest->buildCaseSearchFormTest($normalProducts, 3, '/execution-testcase-3.html', 3)) && p() && e('executionCase');
r($executionTest->buildCaseSearchFormTest($emptyProducts, 0, '/execution-testcase-0.html', 4)) && p() && e('executionCase');
r($executionTest->buildCaseSearchFormTest($normalProducts, 0, '/execution-testcase-boundary.html', 1)) && p() && e('executionCase');
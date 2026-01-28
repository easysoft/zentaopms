#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试companyModel->buildSearchForm();
timeout=0
cid=15730

- 执行companyTest模块的buildSearchFormTest方法，参数是1, '/company-browse-inside-0-id-1.html' 属性queryID @1
- 执行companyTest模块的buildSearchFormTest方法，参数是0, '/company-browse-inside-0-id.html' 属性actionURL @/company-browse-inside-0-id.html
- 执行companyTest模块的buildSearchFormTest方法，参数是5, '' 属性queryID @5
- 执行companyTest模块的buildSearchFormTest方法，参数是10, '/test-action-url.html' 属性actionURL @/test-action-url.html
- 执行companyTest模块的buildSearchFormTest方法，参数是10, '/test-action-url.html' 属性queryID @10

*/

$companyTest = new companyModelTest();
r($companyTest->buildSearchFormTest(1, '/company-browse-inside-0-id-1.html')) && p('queryID') && e('1');
r($companyTest->buildSearchFormTest(0, '/company-browse-inside-0-id.html')) && p('actionURL') && e('/company-browse-inside-0-id.html');
r($companyTest->buildSearchFormTest(5, '')) && p('queryID') && e('5');
r($companyTest->buildSearchFormTest(10, '/test-action-url.html')) && p('actionURL') && e('/test-action-url.html');
r($companyTest->buildSearchFormTest(10, '/test-action-url.html')) && p('queryID') && e('10');
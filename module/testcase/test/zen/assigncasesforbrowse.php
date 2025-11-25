#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCasesForBrowse();
timeout=0
cid=19061

- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是1, '0', 'all', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase' 属性casesCount @20
- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是1, '0', 'all', 0, 0, '', 'id_desc', 0, 10, 2, 'testcase' 属性casesCount @10
- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是1, '0', 'bymodule', 0, 2, '', 'id_desc', 0, 5, 1, 'testcase' 属性casesCount @5
- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是2, '0', 'all', 0, 0, '', 'id_desc', 0, 100, 1, 'testcase' 属性casesCount @5
- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是999, '0', 'all', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase' 属性casesCount @0
- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是1, '0', 'all', 0, 0, '', 'id_desc', 0, 10, 3, 'testcase' 属性hasPager @1
- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是1, '0', 'all', 0, 0, '', 'caseID_desc', 0, 20, 1, 'testcase' 属性orderBy @caseID_desc
- 执行testcaseTest模块的assignCasesForBrowseTest方法，参数是1, '0', 'blocked', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase' 属性hasPager @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zendata('case')->loadYaml('case_assigncasesforbrowse', false, 2)->gen(50);
zendata('product')->loadYaml('product_assigncasesforbrowse', false, 2)->gen(5);
zendata('module')->loadYaml('module_assigncasesforbrowse', false, 2)->gen(10);

su('admin');

$testcaseTest = new testcaseZenTest();

r($testcaseTest->assignCasesForBrowseTest(1, '0', 'all', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase')) && p('casesCount') && e('20');
r($testcaseTest->assignCasesForBrowseTest(1, '0', 'all', 0, 0, '', 'id_desc', 0, 10, 2, 'testcase')) && p('casesCount') && e('10');
r($testcaseTest->assignCasesForBrowseTest(1, '0', 'bymodule', 0, 2, '', 'id_desc', 0, 5, 1, 'testcase')) && p('casesCount') && e('5');
r($testcaseTest->assignCasesForBrowseTest(2, '0', 'all', 0, 0, '', 'id_desc', 0, 100, 1, 'testcase')) && p('casesCount') && e('5');
r($testcaseTest->assignCasesForBrowseTest(999, '0', 'all', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase')) && p('casesCount') && e('0');
r($testcaseTest->assignCasesForBrowseTest(1, '0', 'all', 0, 0, '', 'id_desc', 0, 10, 3, 'testcase')) && p('hasPager') && e('1');
r($testcaseTest->assignCasesForBrowseTest(1, '0', 'all', 0, 0, '', 'caseID_desc', 0, 20, 1, 'testcase')) && p('orderBy') && e('caseID_desc');
r($testcaseTest->assignCasesForBrowseTest(1, '0', 'blocked', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase')) && p('hasPager') && e('1');
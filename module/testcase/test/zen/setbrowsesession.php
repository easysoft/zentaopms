#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setBrowseSession();
timeout=0
cid=19109

- 步骤1：正常设置会话参数属性productID @1
- 步骤2：设置browseType为bymodule属性browseType @bymodule
- 步骤3：设置空的browseType属性moduleID @30
- 步骤4：设置branch为false属性caseBrowseType @wait
- 步骤5：设置所有参数为边界值属性orderBy @pri_asc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseZenTest = new testcaseZenTest();

r($testcaseZenTest->setBrowseSessionTest(1, 'branch1', 10, 'all', 'id_desc')) && p('productID') && e('1'); // 步骤1：正常设置会话参数
r($testcaseZenTest->setBrowseSessionTest(2, 'branch2', 20, 'bymodule', 'name_asc')) && p('browseType') && e('bymodule'); // 步骤2：设置browseType为bymodule
r($testcaseZenTest->setBrowseSessionTest(3, 'branch3', 30, '', 'status_desc')) && p('moduleID') && e('30'); // 步骤3：设置空的browseType
r($testcaseZenTest->setBrowseSessionTest(4, false, 40, 'wait', '')) && p('caseBrowseType') && e('wait'); // 步骤4：设置branch为false
r($testcaseZenTest->setBrowseSessionTest(0, '', 0, 'closed', 'pri_asc')) && p('orderBy') && e('pri_asc'); // 步骤5：设置所有参数为边界值
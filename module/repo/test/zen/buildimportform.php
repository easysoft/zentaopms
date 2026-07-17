#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->buildImportForm();
timeout=0
cid=0

- providerID=1 >> 返回1
- providerID=0 >> 返回0
- providerID=-1 >> 返回0或1
- 带groupID >> 返回1
- 带type参数 >> 返回1

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->buildImportFormTest(1)) && p() && e('1');       // providerID=1
r($zenTest->buildImportFormTest(0)) && p() && e('0');       // providerID=0
r($zenTest->buildImportFormTest(-1)) && p() && e('0');      // providerID=-1
r($zenTest->buildImportFormTest(1, 'group1')) && p() && e('1');       // 带groupID
r($zenTest->buildImportFormTest(1, '', 'type1')) && p() && e('1');    // 带type参数

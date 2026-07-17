#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->locateDiffPage();
timeout=0
cid=0

- 默认参数 >> 返回1
- inline排列 >> 返回1
- sideBySide排列 >> 返回1
- isBranchOrTag=1 >> 返回1
- 带file参数 >> 返回1

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->locateDiffPageTest(1, 0)) && p() && e('1');                       // 默认参数
r($zenTest->locateDiffPageTest(1, 0, 'inline')) && p() && e('1');             // inline排列
r($zenTest->locateDiffPageTest(1, 0, 'sideBySide')) && p() && e('1');         // sideBySide排列
r($zenTest->locateDiffPageTest(1, 0, 'inline', 1)) && p() && e('1');          // isBranchOrTag=1
r($zenTest->locateDiffPageTest(1, 0, 'inline', 0, 'test.php')) && p() && e('1'); // 带file参数

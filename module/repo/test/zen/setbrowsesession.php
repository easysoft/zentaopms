#!/usr/bin/env php
<?php

/**

title=测试 repoZen::setBrowseSession();
timeout=0
cid=0

- 执行repoZenTest模块的setBrowseSessionTest方法 属性backSessionCalled @1
- 执行repoZenTest模块的setBrowseSessionTest方法 属性revisionList @/test/uri
- 执行repoZenTest模块的setBrowseSessionTest方法 属性gitlabBranchList @/test/uri
- 执行repoZenTest模块的setBrowseSessionTest方法
 - 属性backSessionCalled @1
 - 属性revisionList @/test/uri
- 执行repoZenTest模块的setBrowseSessionTest方法 属性uri @/test/uri

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->setBrowseSessionTest()) && p('backSessionCalled') && e('1');
r($repoZenTest->setBrowseSessionTest()) && p('revisionList') && e('/test/uri');
r($repoZenTest->setBrowseSessionTest()) && p('gitlabBranchList') && e('/test/uri');
r($repoZenTest->setBrowseSessionTest()) && p('backSessionCalled,revisionList') && e('1,/test/uri');
r($repoZenTest->setBrowseSessionTest()) && p('uri') && e('/test/uri');
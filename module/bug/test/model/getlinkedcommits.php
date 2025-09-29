#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('repohistory')->gen(0);
zenData('relation')->gen(0);
zenData('bug')->gen(0);

su('admin');

/**

title=bugModel->getLinkedCommits();
timeout=0
cid=1

- 执行bugTest模块的getLinkedCommitsTest方法，参数是1, array  @~~
- 执行bugTest模块的getLinkedCommitsTest方法，参数是999, array  @~~
- 执行bugTest模块的getLinkedCommitsTest方法，参数是1, array  @~~
- 执行bugTest模块的getLinkedCommitsTest方法，参数是1, array  @~~
- 执行bugTest模块的getLinkedCommitsTest方法，参数是2, array  @~~

*/

$bugTest = new bugTest();

r($bugTest->getLinkedCommitsTest(1, array('abc123', 'def456'))) && p() && e('~~');
r($bugTest->getLinkedCommitsTest(999, array('abc123', 'def456'))) && p() && e('~~');
r($bugTest->getLinkedCommitsTest(1, array('nonexistent'))) && p() && e('~~');
r($bugTest->getLinkedCommitsTest(1, array('abc123', 'nonexistent'))) && p() && e('~~');
r($bugTest->getLinkedCommitsTest(2, array('xyz999'))) && p() && e('~~');
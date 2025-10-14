#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getBranchAndTagItems();
timeout=0
cid=0

- 执行repoZenTest模块的getBranchAndTagItemsTest方法 属性selected @master
- 执行repoZenTest模块的getBranchAndTagItemsTest方法 属性selected @develop
- 执行repoZenTest模块的getBranchAndTagItemsTest方法 属性selected @v1.0
- 执行repoZenTest模块的getBranchAndTagItemsTest方法 属性count(*) @0
- 执行repoZenTest模块的getBranchAndTagItemsTest方法 属性selected @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->getBranchAndTagItemsTest((object)array('id' => 1, 'SCM' => 'Git'), 'master')) && p('selected') && e('master');
r($repoZenTest->getBranchAndTagItemsTest((object)array('id' => 1, 'SCM' => 'Git'), 'develop')) && p('selected') && e('develop');
r($repoZenTest->getBranchAndTagItemsTest((object)array('id' => 1, 'SCM' => 'Git'), 'v1.0')) && p('selected') && e('v1.0');
r($repoZenTest->getBranchAndTagItemsTest((object)array('id' => 1, 'SCM' => 'Subversion'), 'trunk')) && p('count(*)') && e('0');
r($repoZenTest->getBranchAndTagItemsTest((object)array('id' => 1, 'SCM' => 'Git'), '')) && p('selected') && e('~~');
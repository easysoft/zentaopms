#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildRepoSearchForm();
timeout=0
cid=0

- 执行repoTest模块的buildRepoSearchFormTest方法，参数是array 属性queryID @5
- 执行repoTest模块的buildRepoSearchFormTest方法，参数是array 属性queryID @0
- 执行repoTest模块的buildRepoSearchFormTest方法，参数是array 属性actionURL @index.php?m=repo&f=maintain&objectID=200&orderBy=id_desc&recPerPage=20&pageID=1&type=bySearch&param=myQueryID
- 执行repoTest模块的buildRepoSearchFormTest方法，参数是array 属性actionURL @index.php?m=repo&f=maintain&objectID=100&orderBy=name_asc&recPerPage=50&pageID=3&type=bySearch&param=myQueryID
- 执行repoTest模块的buildRepoSearchFormTest方法，参数是array 属性onMenuBar @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_buildreposearchform.unittest.class.php';

zenData('product');
zenData('project');

su('admin');

$repoTest = new repoZenBuildRepoSearchFormTest();

r($repoTest->buildRepoSearchFormTest(array('1' => 'Product A', '2' => 'Product B'), array('10' => 'Project X', '20' => 'Project Y'), 100, 'name_asc', 30, 2, 5)) && p('queryID') && e('5');
r($repoTest->buildRepoSearchFormTest(array(), array(), 0, 'id_desc', 20, 1, 0)) && p('queryID') && e('0');
r($repoTest->buildRepoSearchFormTest(array('3' => 'Product C'), array('30' => 'Project Z'), 200, 'id_desc', 20, 1, 0)) && p('actionURL') && e('index.php?m=repo&f=maintain&objectID=200&orderBy=id_desc&recPerPage=20&pageID=1&type=bySearch&param=myQueryID');
r($repoTest->buildRepoSearchFormTest(array('1' => 'Product A'), array('10' => 'Project X'), 100, 'name_asc', 50, 3, 0)) && p('actionURL') && e('index.php?m=repo&f=maintain&objectID=100&orderBy=name_asc&recPerPage=50&pageID=3&type=bySearch&param=myQueryID');
r($repoTest->buildRepoSearchFormTest(array('1' => 'Product A'), array('10' => 'Project X'), 100, 'id_desc', 20, 1, 99)) && p('onMenuBar') && e('yes');
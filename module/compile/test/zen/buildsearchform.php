#!/usr/bin/env php
<?php

/**

title=测试 compileZen::buildSearchForm();
timeout=0
cid=0

- 执行compileTest模块的buildSearchFormTest方法，参数是0, 0, 0 
 - 属性actionURL @compile-browse-0-0-bySearch-myQueryID.html
 - 属性queryID @0
- 执行compileTest模块的buildSearchFormTest方法，参数是1, 0, 0 属性hasRepoField @0
- 执行compileTest模块的buildSearchFormTest方法，参数是0, 1, 0 属性hasRepoField @0
- 执行compileTest模块的buildSearchFormTest方法，参数是1, 1, 0 属性hasRepoField @0
- 执行compileTest模块的buildSearchFormTest方法，参数是0, 0, 5 
 - 属性actionURL @compile-browse-0-0-bySearch-myQueryID.html
 - 属性queryID @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

zenData('repo')->loadYaml('repo_buildsearchform', false, 2)->gen(10);
zenData('compile')->loadYaml('compile_buildsearchform', false, 2)->gen(5);

su('admin');

$compileTest = new compileTest();

r($compileTest->buildSearchFormTest(0, 0, 0)) && p('actionURL,queryID') && e('compile-browse-0-0-bySearch-myQueryID.html,0');
r($compileTest->buildSearchFormTest(1, 0, 0)) && p('hasRepoField') && e('0');
r($compileTest->buildSearchFormTest(0, 1, 0)) && p('hasRepoField') && e('0');
r($compileTest->buildSearchFormTest(1, 1, 0)) && p('hasRepoField') && e('0');
r($compileTest->buildSearchFormTest(0, 0, 5)) && p('actionURL,queryID') && e('compile-browse-0-0-bySearch-myQueryID.html,5');
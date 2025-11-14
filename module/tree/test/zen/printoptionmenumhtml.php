#!/usr/bin/env php
<?php

/**

title=测试 treeZen::printOptionMenuMHtml();
timeout=0
cid=19397

- 执行treeTest模块的printOptionMenuMHtmlTest方法，参数是array  @1
- 执行treeTest模块的printOptionMenuMHtmlTest方法，参数是array  @1
- 执行treeTest模块的printOptionMenuMHtmlTest方法，参数是array  @1
- 执行treeTest模块的printOptionMenuMHtmlTest方法，参数是array  @1
- 执行treeTest模块的printOptionMenuMHtmlTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

su('admin');

$treeTest = new treeTest();

r(strpos($treeTest->printOptionMenuMHtmlTest(array('1' => '模块1', '2' => '模块2'), 'bug', 1), 'loadModuleRelated') !== false) && p() && e('1');
r(strpos($treeTest->printOptionMenuMHtmlTest(array('1' => '模块1', '2' => '模块2'), 'case', 1), 'loadModuleRelated') !== false) && p() && e('1');
r(strpos($treeTest->printOptionMenuMHtmlTest(array('1' => '模块1', '2' => '模块2'), 'task', 1), 'setStories(this.value, 1)') !== false) && p() && e('1');
r(strpos($treeTest->printOptionMenuMHtmlTest(array('1' => '模块1', '2' => '模块2'), 'story', 1), "<select name='module'") !== false) && p() && e('1');
r(strpos($treeTest->printOptionMenuMHtmlTest(array(), 'story', 1), "<select name='module'") !== false) && p() && e('1');
#!/usr/bin/env php
<?php

/**

title=测试 treeZen::updateBrowseLang();
timeout=0
cid=19398

- 执行treeTest模块的updateBrowseLangTest方法，参数是'host' 属性manage @模块维护
- 执行treeTest模块的updateBrowseLangTest方法，参数是'caselib' 属性manage @模块维护
- 执行treeTest模块的updateBrowseLangTest方法，参数是'' 属性manage @模块维护
- 执行treeTest模块的updateBrowseLangTest方法，参数是'story' 属性manage @模块维护
- 执行treeTest模块的updateBrowseLangTest方法，参数是'workflow_test' 属性manage @模块维护

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

su('admin');

$treeTest = new treeTest();

r($treeTest->updateBrowseLangTest('host')) && p('manage') && e('模块维护');
r($treeTest->updateBrowseLangTest('caselib')) && p('manage') && e('模块维护');
r($treeTest->updateBrowseLangTest('')) && p('manage') && e('模块维护');
r($treeTest->updateBrowseLangTest('story')) && p('manage') && e('模块维护');
r($treeTest->updateBrowseLangTest('workflow_test')) && p('manage') && e('模块维护');
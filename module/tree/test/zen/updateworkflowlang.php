#!/usr/bin/env php
<?php

/**

title=测试 treeZen::updateWorkflowLang();
timeout=0
cid=19400

- 执行treeTest模块的updateWorkflowLangTest方法，参数是'datasource_1' 属性manageDatasource_1Child @模块维护
- 执行treeTest模块的updateWorkflowLangTest方法，参数是'workflow_1' 属性manageWorkflow_1Child @模块维护
- 执行treeTest模块的updateWorkflowLangTest方法，参数是'single' 属性manage @维护模块
- 执行treeTest模块的updateWorkflowLangTest方法，参数是'' 属性manage @维护模块
- 执行treeTest模块的updateWorkflowLangTest方法，参数是'datasource_999' 属性manageDatasource_999Child @模块维护

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

su('admin');

$treeTest = new treeTest();

r($treeTest->updateWorkflowLangTest('datasource_1')) && p('manageDatasource_1Child') && e('模块维护');
r($treeTest->updateWorkflowLangTest('workflow_1')) && p('manageWorkflow_1Child') && e('模块维护');
r($treeTest->updateWorkflowLangTest('single')) && p('manage') && e('维护模块');
r($treeTest->updateWorkflowLangTest('')) && p('manage') && e('维护模块');
r($treeTest->updateWorkflowLangTest('datasource_999')) && p('manageDatasource_999Child') && e('模块维护');
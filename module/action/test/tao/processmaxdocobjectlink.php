#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processMaxDocObjectLink();
timeout=0
cid=14965

- 执行actionTest模块的processMaxDocObjectLinkTest方法，参数是1, 'doc', 'view', 'docID=%s'
 - 属性moduleName @assetlib
 - 属性methodName @practiceView
- 执行actionTest模块的processMaxDocObjectLinkTest方法，参数是2, 'doc', 'view', 'docID=%s'
 - 属性moduleName @assetlib
 - 属性methodName @componentView
- 执行actionTest模块的processMaxDocObjectLinkTest方法，参数是5, 'doc', 'view', 'docID=%s'
 - 属性moduleName @doc
 - 属性methodName @view
- 执行actionTest模块的processMaxDocObjectLinkTest方法，参数是1, 'task', 'view', 'taskID=%s'
 - 属性moduleName @assetlib
 - 属性methodName @taskView
- 执行actionTest模块的processMaxDocObjectLinkTest方法，参数是999, 'doc', 'view', 'docID=%s'
 - 属性moduleName @doc
 - 属性methodName @view

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('doc')->loadYaml('doc_processmaxdocobjectlink', false, 2)->gen(10);

su('admin');

$actionTest = new actionTest();

r($actionTest->processMaxDocObjectLinkTest(1, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('assetlib,practiceView');
r($actionTest->processMaxDocObjectLinkTest(2, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('assetlib,componentView');
r($actionTest->processMaxDocObjectLinkTest(5, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('doc,view');
r($actionTest->processMaxDocObjectLinkTest(1, 'task', 'view', 'taskID=%s')) && p('moduleName,methodName') && e('assetlib,taskView');
r($actionTest->processMaxDocObjectLinkTest(999, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('doc,view');